<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);


$userId = $USER->GetID();
$arUserGroups = CUser::GetUserGroup($userId);
$findGroupAdmin = in_array("1", $arUserGroups);
$findGroupMFC = in_array("5", $arUserGroups);
$findGroupKeys = in_array("6", $arUserGroups);


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


// Инфоблок "Заявки"
$_elRequests = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => 1], false, false, []);
while ($_elRequestsObj = $_elRequests->GetNextElement()) {
    $elRequestsFields = $_elRequestsObj->GetFields();

    if ($elRequestsFields['ID'] == $_GET['ELEMENT_ID']) {
        $requestsArr[$elRequestsFields['ID']]['ELEMENT'] = $elRequestsFields;


        $_elRequestsProp = CIBlockElement::GetProperty(1, $_GET['ELEMENT_ID'], [], []);
        while ($elRequestsPropFields = $_elRequestsProp->Fetch()) {
            $requestsArr[$elRequestsFields['ID']]['ELEMENT']['PROP'][$elRequestsPropFields['CODE']] = $elRequestsPropFields;

        }
    }
}

// Вывод обсуждений к текущей заявке

$discussionsArr = [];

// Инфоблок "Обсуждение"
$_elDiscussions = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => 2], false, false, []);
while ($_elDiscussionsObj = $_elDiscussions->GetNextElement()) {
    $elDiscussionsFields = $_elDiscussionsObj->GetFields();

    $discussionsArr[$elDiscussionsFields['ID']]['ELEMENT'] = $elDiscussionsFields;


    $_elDiscussionsProp = CIBlockElement::GetProperty(2, $elDiscussionsFields['ID'], [], []);
    while ($elDiscussionsPropFields = $_elDiscussionsProp->Fetch()) {
        $discussionsArr[$elDiscussionsFields['ID']]['ELEMENT']['PROP'][$elDiscussionsPropFields['CODE']] = $elDiscussionsPropFields;

    }
}

$discussionsArrSort = [];

// Выборка обсуждений по текущей заявке
foreach ($discussionsArr as $key) {
    if ($key['ELEMENT']['PROP']['ID_REQUEST']['VALUE'] !== $arResult['ID']) {
        // удаляем сообщения не относящиеся к заявке
        unset($discussionsArr[$key['ELEMENT']['ID']]);
    } else {
        $discussionsArrSort[$key['ELEMENT']['PROP']['ID_MESSAGE_QUEUES_FOR_THE_TICKET']['VALUE']] = $key['ELEMENT'];
    }
}

ksort($discussionsArrSort, SORT_NUMERIC);

// Вывод обсуждений к текущей заявке end

?>

<?php if ($findGroupAdmin || $findGroupMFC || $findGroupKeys): ?>

    <?php

    $strToArrGetUsers = json_decode($arResult['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);
    $customerId = json_decode($arResult['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

    // Меняем местами ключи с их значениями в массиве
    $flipArray = array_flip($customerId);

    // array_key_exists присутствует ли в массиве указанный ключ или индекс
    if (array_key_exists((int)$userId, $flipArray) || $findGroupAdmin || $findGroupKeys): ?>

        <div class="keys-translr__box_wrap-request">
            <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
                <div id="humburger-btn" class="humburger-wrap">
                    <span class="humburger-line humburger-start"></span>
                    <span class="humburger-line humburger-middle"></span>
                    <span class="humburger-line humburger-end"></span>
                </div>
                <div class="fjc-s fai-c">
                    <div><img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo-blue.svg" alt="logo-blue"/></div>
                    <div class="pl-10 color__blue-light fs-20 fontw-700">
                        Переводы
                    </div>
                </div>
            </div>

            <div class="pl-60 pr-60">
                <div class="keys-translr__box-discussion-header">
                    <div class="fw-wrap fjc-s fai-c mb-30">
                        <div class="fjc-s mr-40">
                            <a href="/list/" class=" fjc-s keys-translr__box-discussion-header_back-link mb-20">
                        <span class="pr-10">
                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/left-arrow.svg" alt="left-arrow"/>
                        </span>
                                Все заявки
                            </a>
                        </div>
                        <h1 class="color__black fs-28 mb-20">
                            <?= $arResult['NAME'] ?>
                        </h1>
                    </div>
                    <div class="fw-wrap fjc-s fai-c mb-30">
                        <h3 class="color__black fs-28">Заявка № <span><?= $arResult['ID']; ?></span></h3>
                    </div>
                </div>

                <div class="keys-translr__box-discussion-content pt-30 pb-30 pl-30 pr-30">

                    <?php
                    $arrAllCurrentUsersMFC = [];

                    if (is_array($strToArrGetUsers)) {

                        foreach ($strToArrGetUsers as $keyId) {
                            $currentUserCustomer = $USER::GetByID($keyId);
                            $arCurrentUserCustomer = $currentUserCustomer->Fetch(); // данные о пользователе
                            $arrAllCurrentUsersMFC[$arCurrentUserCustomer['ID']] = $arCurrentUserCustomer;
                        }
                    } else {
                        $currentUserCustomer = $USER::GetByID($idUserCustomer);
                        $arCurrentUserCustomer = $currentUserCustomer->Fetch(); // данные о пользователе
                    }

                    ?>

                    <!-- Заявка в закрепе -->
                    <div class="bground__white mb-40">

                        <div id="io-new-user">
                            <div class="pt-20 pb-20 pl-20 pr-20 color__black fs-16">

                                <?php
                                // Выводим данные, если есть доступ к заявке больше одного пользователя
                                if (isset($strToArrGetUsers) && !empty($strToArrGetUsers) && is_array($strToArrGetUsers)): ?>

                                    <?php

                                    foreach ($arrAllCurrentUsersMFC as $key): ?>

                                        <div id="box__user-from-request-<?= $key['ID'] ?>"  class="pb-10">
                                            <span class="fontw-700">Контакты:</span>
                                            <span class="keys-translr__box-discussion-content_files">
                                                <span><?= ucfirst($key['NAME']) ?></span> |
                                                <a class="color__blue-light" href="mailto:<?= $key['EMAIL'] ?>">
                                                    <?= $key['EMAIL'] ?>
                                                </a>
                                                <?php
                                                if(count($arrAllCurrentUsersMFC) > 1):
                                                    $arCurrentData = [];
                                                    $arCurrentData['user-id'] = $key['ID'];
                                                    $arCurrentData['req-id'] = $arResult['ID'];
                                                ?>
                                                <span class="rem-user-from-request ml-10" data-o-cust-user='<?= json_encode($arCurrentData) ?>'>&#215;</span>
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
                                                    var data = JSON.parse(resJson);
                                                    $.ajax({
                                                        url: '/ajax/io-new-user/',
                                                        data: {el: data.el, users: data.users},
                                                        type: 'post',
                                                        success: function (resHtml) {
                                                            $('#io-new-user').html(resHtml);
                                                            alertify.success('Удален пользователь №' + dataJson['user-id']);
                                                        }
                                                    });
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
                                <div class="mb-10">
                                    <span class="fontw-700">Дата создания заявки:</span>
                                    <span>
                                        <?php
                                        $arrDataCreate = CIBlockElement::GetByID($arResult["ID"]);
                                        if ($arrDataCreateRes = $arrDataCreate->GetNext()):?>
                                            <?= $arrDataCreateRes['DATE_CREATE']; ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="mb-20">
                                    <span class="fontw-700">Дата изменения заявки:</span>
                                    <span><?= $arResult['TIMESTAMP_X']; ?></span>
                                </div>

                                <div class="mb-20">
                                    <form id="form-add-to-user-request">
                                        <div>
                                            <div class="mb-10">
                                                <label for="keys-translr__box-form_ready-documents">Добавить пользователя:</label>
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

                                        <input type="hidden" name="request-id" value="<?= $arResult['ID']; ?>">
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

                        </div>


                        <div class="fjc-sb fw-wrap">
                            <div class="fjc-sb pt-20 pb-20 pl-20 pr-20 flex__50">
                                <div class="fjc-sb">

                                    <div>
                                        <ul class="fs-16">
                                            <li>
                                                <span class="fontw-700">ФИО:</span> <?= $arResult['PROPERTIES']['CUSTOMER_FIO']['VALUE']; ?>
                                            </li>

                                            <li>
                                                <span class="fontw-700">Телефон:</span> <?= $arResult['PROPERTIES']['CUSTOMER_PHONE']['VALUE']; ?>
                                            </li>

                                            <?php if (!empty($arResult['PROPERTIES']['CUSTOMER_COMMENT']['VALUE'])): ?>
                                                <li>
                                                    <span class="fontw-700">Комментарий:</span> <?= $arResult['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']; ?>
                                                </li>
                                            <?php endif; ?>

                                            <li>
                                                <span class="fontw-700">Место выдачи документов:</span> <?= $arResult['PROPERTIES']['READY_DOCUMENTS']['VALUE']; ?>
                                            </li>

                                            <li>
                                                <span class="fontw-700">Статус:</span>
                                                <?php

                                                switch ($arResult['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                                    case (6): // В работе  6
                                                        echo '<span class="status-str__primary">В работе</span>';
                                                        break;
                                                    case (7): // Срочная  7
                                                        echo '<span class="status-str__danger">Срочная</span>';
                                                        break;
                                                    case (8): // Выполненная  8
                                                        echo '<span class="status-str__success">Перевод выполнен</span>';
                                                        break;
                                                    case (9): // Ошибка в переводе 9
                                                        echo '<span class="status-str__warning">Ошибка в переводе</span>';
                                                        break;
                                                    case (10): // Завершена 10
                                                        echo '<span class="status-str__default">Завершена</span>';
                                                        break;
                                                }

                                                ?>
                                            </li>
                                        </ul>
                                        <div class="mt-20 mb-20">
                                            <div class="fontw-700 fs-16 mb-10">Файлы для перевода:</div>
                                            <ul class="keys-translr__box-discussion-content_files fjc-s fw-wrap">

                                                <?php

                                                $attachedFilesTranslate = json_decode($arResult['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'], true);

                                                foreach ($attachedFilesTranslate as $key):

                                                    $urlFile = CFile::GetPath($key);
                                                    $dataFile = CFile::GetByID($key);
                                                    $arrDataFile = $dataFile->Fetch();

                                                    ?>

                                                    <li class="fjc-s fai-e mb-10 mr-30">
                                                          <span class="pr-10">
                                                              <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg"
                                                                   alt="file"/>
                                                          </span>
                                                        <a href="<?= $urlFile; ?>" target="_blank"
                                                           class="fs-16 color__blue-light"><?= $arrDataFile['ORIGINAL_NAME']; ?></a>
                                                    </li>

                                                <?php endforeach; ?>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex__35" id="state-current-data-request">
                                <div class="fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
                                    <div>
                                        <span class="fontw-700">Статус:</span>

                                        <?php

                                        switch ($arResult['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                            case (6): // В работе  6
                                                echo '<span class="status-str__primary">В работе</span>';
                                                break;
                                            case (7): // Срочная  7
                                                echo '<span class="status-str__danger">Срочная</span>';
                                                break;
                                            case (8): // Выполненная  8
                                                echo '<span class="status-str__success">Перевод выполнен</span>';
                                                break;
                                            case (9): // Ошибка в переводе 9
                                                echo '<span class="status-str__warning">Ошибка в переводе</span>';
                                                break;
                                            case (10): // Завершена 10
                                                echo '<span class="status-str__default">Завершена</span>';
                                                break;
                                        }

                                        ?>
                                    </div>

                                    <?php

                                    if (!empty($arResult['CODE']) && $arResult["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 8 || !empty($arResult['CODE']) && $arResult["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 10): ?>
                                        <div>
                                            <div>
                                                <span class="fontw-700">Время действия ссылки:</span>

                                                <div>
                                                    <?php $strToArrLinkLife = json_decode($arResult['PROPERTIES']['LINK_LIFE_DATE']['VALUE'], true); ?>
                                                    <span>с </span> <?= date("d-m-Y h:i:s", $strToArrLinkLife[0]); ?>
                                                </div>

                                                <div>
                                                    <span>по </span> <?= date("d-m-Y h:i:s", $strToArrLinkLife[1]); ?>
                                                </div>

                                            </div>
                                        </div>
                                        <div>
                                            <span class="fontw-700">Ссылка:</span>
                                            <a class="keys-translr__box-discussion-content_transfer-link"
                                               target="_blank" href="/transfer-view/?hash=<?= $arResult['CODE']; ?>">Страница
                                                с переводом</a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Заявка в закрепе end -->

                    <!-- Обсуждение -->
                    <div class="mb-20">
                        <h2 class="color__black fs-18">Обсуждение</h2>
                    </div>

                    <div class="keys-translr__box-discussion-view-bef-aft pl-20 pr-20 pt-20 pb-20">
                        <div class="keys-translr__box-discussion-view">


                            <?php

                            // id 5 группа мфц
                            // id 6 группа ключи

                            // dump($discussionsArrSort);

                            foreach ($discussionsArrSort as $key): ?>

                                <?php

                                $strToArrGroupUser = json_decode($key['PROP']['GROUP_USERS']['VALUE'], true);

                                // вывод для "Переводчика"
                                if (array_search(6, $strToArrGroupUser)): ?>

                                    <?php

                                    // выводим данные по группе
                                    $lastIdGroup = end($strToArrGroupUser);
                                    $propGroup = CGroup::GetByID($lastIdGroup, "Y");
                                    $arGroup = $propGroup->Fetch();


                                    // данные по пользователю
                                    $idUser = $key['PROP']['ID_USERS']['VALUE'];
                                    $currentUser = $USER::GetByID($idUser);
                                    $arCurrentUser = $currentUser->Fetch(); // данные о пользователе

                                    ?>

                                    <!-- Обсуждение от лица переводчика -->
                                    <div class="keys-translr__box-discussion-bshadow mb-40">
                                        <div class=" keys-translr__box-discussion-executor pt-20 pb-20 pl-20 pr-20 color__white fs-16">
                                            <span><?= trim($arGroup['NAME']); ?>:</span>
                                            <span><?= $arCurrentUser['EMAIL']; ?> | </span>
                                            <span><?= $key['TIMESTAMP_X']; ?></span>
                                        </div>
                                        <div class="fjc-sb fw-wrap">
                                            <div class="fjc-s pt-20 pb-20 pl-20 pr-20 flex__50">
                                                <div class="executor-logo pr-40 mb-20">
                                                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/executor.svg"
                                                         alt="executor"/>
                                                </div>
                                                <div>
                                                    <div class="color__black fs-16 mb-10">
                                                        <?= $key['NAME']; ?>
                                                    </div>
                                                    <div class="color__black fs-16">
                                                        <?= $key['PROP']['COMMENT']['VALUE']['TEXT']; ?>
                                                    </div>
                                                    <div class="mt-20 mb-20">

                                                        <ul class="keys-translr__box-discussion-content_files fjc-s">

                                                            <?php

                                                            $attachedFilesTranslate = json_decode($key['PROP']['ATTACHED_FILES']['VALUE'], true);

                                                            foreach ($attachedFilesTranslate as $keyFiles):

                                                                $urlFile = CFile::GetPath($keyFiles);
                                                                $dataFile = CFile::GetByID($keyFiles);
                                                                $arrDataFile = $dataFile->Fetch();
                                                                //dump($arrDataFile);

                                                                ?>

                                                                <li class="fjc-s fai-e mb-10 mr-30">
                                                            <span class="pr-10">
                                                                <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg"
                                                                     alt="file"/>
                                                            </span>
                                                                    <a href="<?= $urlFile; ?>"
                                                                       class="fs-16 color__blue-light"><?= $arrDataFile['ORIGINAL_NAME']; ?></a>
                                                                </li>

                                                            <?php endforeach; ?>

                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fjc-sb flex__35">

                                                <?php if (!empty($key['PROP']['STATUS']['VALUE'])): ?>

                                                    <div class="fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
                                                        <div>

                                                            <?php

                                                            switch ($key["PROP"]["STATUS"]["VALUE"]) {

                                                                case 11:
                                                                    // echo '<span class="status-str status-str__primary">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 12:
                                                                    echo '<span class="fontw-700">Статус:</span>';
                                                                    echo '<span class="status-str status-str__danger">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 13:
                                                                    echo '<span class="fontw-700">Статус:</span> ';
                                                                    echo '<span class="status-str status-str__success">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 14:
                                                                    echo '<span class="fontw-700">Статус:</span>';
                                                                    echo '<span class="status-str status-str__warning">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 15:
                                                                    echo '<span class="fontw-700">Статус:</span>';
                                                                    echo '<span class="status-str status-str__default">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                            }

                                                            ?>


                                                        </div>

                                                        <?php if ($key["PROP"]["STATUS"]["VALUE"] == 13): ?>
                                                            <div>
                                                                <span class="fontw-700">Стоимость:</span>
                                                                <span class="status-str status-str__warning"><?= $arResult['PROPERTIES']['FIELD_PRICE']['VALUE']; ?> ₽</span>
                                                            </div>

                                                            <div>
                                                                <span class="fontw-700">Ссылка:</span>
                                                                <a class="keys-translr__box-discussion-content_transfer-link"
                                                                   target="_blank"
                                                                   href="/transfer-view/?hash=<?= $arResult['CODE']; ?>">
                                                                    Страница перевода
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>


                                                    </div>

                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Обсуждение от лица переводчика end -->

                                <?php else: ?>

                                    <?php
                                    // выводим данные по группе
                                    $lastIdGroup = end($strToArrGroupUser);
                                    $propGroup = CGroup::GetByID($lastIdGroup, "Y");
                                    $arGroup = $propGroup->Fetch();


                                    // данные по пользователю
                                    $idUser = $key['PROP']['ID_USERS']['VALUE'];
                                    $currentUser = $USER::GetByID($idUser);
                                    $arCurrentUser = $currentUser->Fetch(); // данные о пользователе

                                    ?>

                                    <!-- Обсуждение от лица заказчика -->
                                    <div class="keys-translr__box-discussion-bshadow mb-40">
                                        <div class=" keys-translr__box-discussion-customer pt-20 pb-20 pl-20 pr-20 color__white fs-16">
                                            <span><?= trim($arGroup['NAME']); ?>:</span>
                                            <span><?= $arCurrentUser['EMAIL']; ?> |</span>
                                            <span><?= $key['TIMESTAMP_X']; ?></span>
                                        </div>
                                        <div class="fjc-sb fw-wrap">
                                            <div class="fjc-s pt-20 pb-20 pl-20 pr-20 flex__50">
                                                <div class="customer-logo pr-40 mb-20">
                                                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/customer.svg"
                                                         alt="customer"/>
                                                </div>
                                                <div>
                                                    <div class="color__black fs-16 mb-10">
                                                        <?= $key['NAME']; ?>
                                                    </div>
                                                    <div class="color__black fs-16">
                                                        <?= $key['PROP']['COMMENT']['VALUE']['TEXT']; ?>
                                                    </div>
                                                    <div class="mt-20 mb-20">
                                                        <ul class="keys-translr__box-discussion-content_files fjc-s">


                                                            <?php

                                                            $attachedFilesTranslate = json_decode($key['PROP']['ATTACHED_FILES']['VALUE'], true);

                                                            foreach ($attachedFilesTranslate as $keyFiles):

                                                                $urlFile = CFile::GetPath($keyFiles);
                                                                $dataFile = CFile::GetByID($keyFiles);
                                                                $arrDataFile = $dataFile->Fetch();
                                                                //dump($arrDataFile);

                                                                ?>

                                                                <li class="fjc-s fai-e mb-10 mr-30">
                                                            <span class="pr-10">
                                                                <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg"
                                                                     alt="file"/>
                                                            </span>
                                                                    <a href="<?= $urlFile; ?>"
                                                                       class="fs-16 color__blue-light"><?= $arrDataFile['ORIGINAL_NAME']; ?></a>
                                                                </li>

                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="fjc-sb flex__35">

                                                <?php

                                                if (!empty($key['PROP']['STATUS']['VALUE'])): ?>

                                                    <div class="fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
                                                        <div>
                                                            <span class="fontw-700">Статус:</span>

                                                            <?php

                                                            switch ($key["PROP"]["STATUS"]["VALUE"]) {

                                                                case 11:
                                                                    echo '<span class="status-str status-str__primary">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 12:
                                                                    echo '<span class="status-str status-str__danger">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 13:
                                                                    echo '<span class="status-str status-str__success">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 14:
                                                                    echo '<span class="status-str status-str__warning">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                                case 15:
                                                                    echo '<span class="status-str status-str__default">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                                                    break;
                                                            }

                                                            ?>

                                                        </div>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- Обсуждение от лица заказчика end -->

                                <?php endif; ?>


                            <?php endforeach; ?>

                        </div>
                    </div>
                    <!-- Обсуждение end -->

                    <!-- Форма -->
                    <div class="mt-80">

                        <?php

                        $rsUser = $USER::GetByID($USER->GetID());
                        $arUser = $rsUser->Fetch(); // данные о пользователе
                        $arGroups = $USER->GetUserGroupArray(); // id групп пользователя
                        $arGroupsStr = implode(',', $arGroups);
                        // dump($arGroups);

                        $findGroupKeys = in_array("6", $arGroups); // Агентство переводов ООО «Ключи»
                        // dump($findGroupKeys);

                        ?>

                        <?php
                        // Если переводчик
                        if ($findGroupKeys): ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/form/translator/index.php"
                                )
                            ); ?>

                        <?php else: ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/form/customer/index.php"
                                )
                            );

                            ?>

                        <?php endif; ?>

                    </div>
                    <!-- Форма end -->
                </div>


            </div>
        </div>

    <?php else: ?>

        <div class="keys-translr__box_wrap-request mt-60 pl-60 pr-60">
            <div class="fjc-c mb-30">
                <h1 class="color__black fs-28">
                    <span class="status-str__danger">Доступ ограничен!</span>
                </h1>
            </div>
        </div>

    <?php endif; ?>

<?php else: ?>

    <div class="mt-60 pl-60 pr-60">
        <div class="fjc-c mb-30">
            <h1 class="color__black fs-28">
                <span class="status-str__danger">Доступ ограничен!</span>
            </h1>
        </div>
    </div>

<?php endif; ?>