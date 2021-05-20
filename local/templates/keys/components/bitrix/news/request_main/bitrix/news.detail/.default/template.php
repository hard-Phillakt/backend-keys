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

// dump($arParams);

// dump($arResult);

// discussion

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
    }else {
        $discussionsArrSort[$key['ELEMENT']['PROP']['ID_MESSAGE_QUEUES_FOR_THE_TICKET']['VALUE']] = $key['ELEMENT'];
    }
}

ksort($discussionsArrSort, SORT_NUMERIC);

//dump($discussionsArrSort);

?>

<div class="col-lg-12">
    <h1><?= $arResult['NAME']; ?></h1>
    <ul>
        <li>Создана: <?= $arResult['TIMESTAMP_X']; ?></li>
        <li>Статус: <?= $arResult['PROPERTIES']['STATUS']['VALUE']; ?></li>
        <li>Ответственный: <?= $arResult['PROPERTIES']['CUSTOMER_FIO']['VALUE'] . " | " . $arResult['TIMESTAMP_X']; ?></li>
    </ul>
</div>


<div class="col-lg-12">

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h2>Обсуждение</h2>
            </div>
        </div>
    </div>

    <div class="well">

        <!--    Информация по заявке    -->
        <div class="panel panel-primary">
            <div class="panel-heading"><h3 class="panel-title">Panel title</h3></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="col-lg-1">
                            <div>
                                <img src="<?= DEFAULT_TEMPLATE_PATH . '/img/user/user-customer.png' ?>" alt="">
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <ul>
                                <li>ФИО: Семенов Николай Михайлович</li>
                                <li>Телефон: +7 910 111-22-33</li>
                                <li>Комментарий: Нужно сделать срочно</li>
                            </ul>
                            <ul>
                                <li>Доверенность.pdf</li>
                                <li>Доверенность.pdf</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <?php

        // id 5 группа мфц
        // id 6 группа ключи

         dump($discussionsArrSort);

        foreach ($discussionsArrSort as $key): ?>

            <?php

            $strToArrGroupUser = json_decode($key['PROP']['GROUP_USERS']['VALUE'], true);

            // вывод для "Переводчика"
            if(array_search(6, $strToArrGroupUser)): ?>

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

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?= trim($arGroup['NAME']); ?>
                            : <?= $arCurrentUser['EMAIL']; ?>
                            | <?= $key['TIMESTAMP_X']; ?></h3>
                    </div>
                    <div class="panel-body">Panel content</div>
                </div>



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

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?= trim($arGroup['NAME']); ?>
                            : <?= $arCurrentUser['EMAIL']; ?>
                            | <?= $key['TIMESTAMP_X']; ?></h3>
                    </div>
                    <div class="panel-body">Panel content</div>
                </div>


            <?php endif; ?>


        <?php endforeach; ?>


    </div>


    <?php

    $rsUser = $USER::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch(); // данные о пользователе
    $arGroups = $USER->GetUserGroupArray(); // id групп пользователя
    ?>

    <div class="well">
        <div class="row">
            <div class="col-lg-6">
                <h3>Заказчик</h3>
                <form action="/ajax/discussion-el-add/index.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputTitle">Заголовок</label>
                        <input type="text" name="title" class="form-control" id="exampleInputTitle" placeholder="Введите заголовок" value="Заказчик title">
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea">Ответить</label>
                        <textarea class="form-control" name="comment" rows="5" id="exampleTextarea">Заказчик text</textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Файл для перевода</label>
                        <input type="file" id="exampleInputFile" name="upfiles[]" accept=".doc, .docx, .pdf, .jpg, .png" multiple>
                        <p class="help-block">Выбрать файл.</p>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="radio" name="status" value="11" checked> В работе
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="radio" name="status" value="12"> Срочная
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="radio" name="status" value="13"> Выполненная
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="radio" name="status" value="14"> Ошибка в переводе
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="radio" name="status" value="15"> Завершена
                        </label>
                    </div>

                    <input type="hidden" name="idGroupUser" value='<?= json_encode($arGroups); ?>'>
                    <input type="hidden" name="idRequest" value="<?= $_GET['ELEMENT_ID'] ?>">
                    <input type="hidden" name="action" value="addElementDiscussionCustomer">

                    <button type="submit" class="btn btn-success">Отправить</button>

                </form>
            </div>

            <div class="col-lg-6">
                <h3>Переводчик</h3>
                <form action="/ajax/discussion-el-add/index.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputTitle">Заголовок</label>
                        <input type="text" name="title" class="form-control" id="exampleInputTitle" placeholder="Введите заголовок" value="Переводчик title">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPrice">Цена за перевод</label>
                        <input type="text" name="price" class="form-control" id="exampleInputPrice" placeholder="Цена за перевод" value="1000">
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea">Ответить</label>
                        <textarea class="form-control" name="comment" rows="5" id="exampleTextarea">Переводчик text</textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="startLiveLinks">Начало активности ссылки</label>
                                <input type="text" name="startLiveLinks" class="form-control datepicker" id="startLiveLinks" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="endLiveLinks">Конец активности ссылки</label>
                                <input type="text" name="endLiveLinks" class="form-control datepicker" id="endLiveLinks" >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Файл для перевода</label>
                        <input type="file" id="exampleInputFile" name="upfiles[]" accept=".doc, .docx, .pdf, .jpg, .png" multiple>
                        <p class="help-block">Выбрать файл.</p>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status" value="13"> Перевод выполнен
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="createLink"> сформировать ссылку
                        </label>
                    </div>

                    <input type="hidden" name="idGroupUser" value='<?= json_encode($arGroups); ?>'>
                    <input type="hidden" name="idRequest" value="<?= $_GET['ELEMENT_ID'] ?>">
                    <input type="hidden" name="action" value="addElementDiscussionTranslator">

                    <button type="submit" class="btn btn-success">Отправить</button>

                </form>

            </div>
        </div>
    </div>
</div>
