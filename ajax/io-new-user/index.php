<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");

if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false):

global $USER;

// Выборка и сортировка пользователей из группы MFC
$arAllUsers = [];
$order = ["sort" => "asc"];
$tmp = 'sort';
$rsUsers = CUser::GetList($order, $tmp);

while ($arUser = $rsUsers->Fetch()) {

    $getUserGroup = CUser::GetUserGroup($arUser['ID']);

    $arAllUsers[$arUser['ID']] = [
        'USER' => $arUser,
        'GROUP' => $getUserGroup
    ];
}

if (CModule::IncludeModule("iblock")) {
    $requestsArr = [];

    // Инфоблок "Заявки"
    $_elRequests = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => 1], false, false, []);
    while ($_elRequestsObj = $_elRequests->GetNextElement()) {
        $elRequestsFields = $_elRequestsObj->GetFields();

        if ($elRequestsFields['ID'] == $_POST['el']) {
            $requestsArr[$elRequestsFields['ID']]['ELEMENT'] = $elRequestsFields;


            $_elRequestsProp = CIBlockElement::GetProperty(1, $_POST['el'], [], []);
            while ($elRequestsPropFields = $_elRequestsProp->Fetch()) {
                $requestsArr[$elRequestsFields['ID']]['ELEMENT']['PROP'][$elRequestsPropFields['CODE']] = $elRequestsPropFields;
            }
        }
    }

    $strToArrGetUsers = json_decode($_POST['users'], true);

    $arrAllCurrentUsersMFC = [];
    if (is_array($strToArrGetUsers)) {
        foreach ($strToArrGetUsers as $keyId) {
            $currentUserCustomer = $USER::GetByID($keyId);
            $arCurrentUserCustomer = $currentUserCustomer->Fetch(); // данные о пользователе
            $arrAllCurrentUsersMFC[$arCurrentUserCustomer['ID']] = $arCurrentUserCustomer;
        }
    }
}

?>

<div class="pt-20 pb-20 pl-20 pr-20 color__black fs-16">

    <?php
    // Выводим данные, если есть доступ к заявке больше одного пользователя
    if (isset($strToArrGetUsers) && !empty($strToArrGetUsers) && is_array($strToArrGetUsers)): ?>

        <?php foreach ($arrAllCurrentUsersMFC as $key): ?>

            <div id="box__user-from-request-<?= $key['ID'] ?>"  class="pb-10">
                <span class="fontw-700">Контакты:</span>
                <span class="keys-translr__box-discussion-content_files">
                    <span><?= ucfirst($key['NAME']) ?></span> |
                    <a class="color__blue-light" href="mailto:<?= $key['EMAIL'] ?>">
                        <?= $key['EMAIL'] ?>
                    </a>
                    <?php
                    if (count($arrAllCurrentUsersMFC) > 1):
                        $arCurrentData = [];
                        $arCurrentData['user-id'] = $key['ID'];
                        $arCurrentData['req-id'] = $_POST['el'];
                        ?>
                        <span class="rem-user-from-request ml-10"
                              data-o-cust-user='<?= json_encode($arCurrentData) ?>'>&#215;</span>
                    <?php endif; ?>
                </span>
            </div>

            <?php
            unset($key);
        endforeach; ?>

    <?php else: ?>

        <div>
            <span class="fontw-700">Контакты:</span>
            <span class="keys-translr__box-discussion-content_files">
                <span><?= ucfirst($arCurrentUserCustomer['NAME']) ?></span> |
                <a class="color__blue-light"
                   href="mailto:<?= $arCurrentUserCustomer['EMAIL'] ?>">
                    <?= $arCurrentUserCustomer['EMAIL'] ?>
                </a>
            </span>
        </div>

    <?php endif; ?>

    <script>
        $('.rem-user-from-request').on('click', function () {
            var dataJson = $(this).data('o-cust-user');

            $.ajax({
                url: '/ajax/o-customer-user/',
                data: $(this).data('o-cust-user'),
                type: 'post',
                success: function (resJson) {
                    console.log(resJson);
                    if(resJson){
                        // $('#box__user-from-request-' + dataJson['user-id']).remove();

                        var data = JSON.parse(resJson);

                        $.ajax({
                            url: '/ajax/io-new-user/',
                            data: {el: data.el, users: data.users},
                            type: 'post',
                            success: function (resHtml) {
                                $('#io-new-user').html(resHtml);
                            }
                        });

                        alertify.success('Удален пользователь №' + dataJson['user-id']);
                    }else {
                        alertify.error('Произошла ошибка!' + dataJson['user-id']);
                    }
                }
            });
        });
    </script>

    <?php

    // Ищем не добавленных пользователй из группы МФЦ
    $arAddCustomerUsers = array_diff_key($arAllUsers, $arrAllCurrentUsersMFC);

    ?>

    <div class="mb-20">
        <span class="fontw-700">Дата создания заявки:</span>
        <span><?= $requestsArr[$_POST['el']]['ELEMENT']['TIMESTAMP_X']; ?></span>
    </div>

    <div class="mb-20">
        <form id="form-add-to-user-request">
            <div>
                <div class="mb-10">
                    <label for="keys-translr__box-form_ready-documents">Добавить
                        пользователя:</label>
                </div>
                <div class="custom-select mb-20">
                    <select name="i-customer-user">
                        <option value="">-</option>
                        <option value="">-</option>

                        <?php foreach ($arAddCustomerUsers as $key): ?>

                            <?php

                            // 5 - id группы МФЦ
                            if (in_array("5", $key['GROUP'])): ?>
                                <option value="<?= $key['USER']['ID']; ?>"><?= $key['USER']['LOGIN']; ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <div>
                <button type="submit" id="add-to-user-request"
                        class="button__primary color__white pl-60 pr-60 pt-10 pb-10 fs-16">
                    Добавить в заявку
                </button>
            </div>

            <input type="hidden" name="request-id" value="<?= $_POST['el']; ?>">
        </form>
    </div>

    <script>

        $('#form-add-to-user-request').on('submit', function (e) {
            e.preventDefault();
            var formArr = $(this).serializeArray();

            if (formArr[0].value !== '') {

                $.ajax({
                    url: '/ajax/i-customer-user/',
                    data: $(this).serialize(),
                    type: 'post',
                    success: function (resJson) {
                        var data = JSON.parse(resJson);
                        $.ajax({
                            url: '/ajax/io-new-user/',
                            data: {el: data.el, users: data.users},
                            type: 'post',
                            success: function (resHtml) {
                                $('#io-new-user').html(resHtml);
                                alertify.success('Добавлен новый пользователь!');
                            }
                        });
                    }
                });
            }else {
                alertify.warning('Выберите пользователя');
            }

        });

        // Инициализируем вывод кастомного Select-a
        customSelect();

    </script>

</div>

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>