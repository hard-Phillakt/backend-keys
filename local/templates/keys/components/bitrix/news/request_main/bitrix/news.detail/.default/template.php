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
    } else {
        $discussionsArrSort[$key['ELEMENT']['PROP']['ID_MESSAGE_QUEUES_FOR_THE_TICKET']['VALUE']] = $key['ELEMENT'];
    }
}

ksort($discussionsArrSort, SORT_NUMERIC);

// dump($discussionsArrSort);


?>

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
                <h1 class="color__black fs-28 mb-20"><?= $arResult['NAME']; ?></h1>
            </div>
            <div class="fw-wrap fjc-s fai-c mb-30">
                <h3 class="color__black fs-28">Заявка № <span><?= $arResult['ID']; ?></span></h3>
            </div>
        </div>

        <div class="keys-translr__box-discussion-content pt-30 pb-30 pl-30 pr-30">


            <?php

            // dump($arResult);

            $idUserCustomer = $arResult['PROPERTIES']['CUSTOMER_ID']['VALUE'];
            $currentUserCustomer = $USER::GetByID($idUserCustomer);
            $arCurrentUserCustomer = $currentUserCustomer->Fetch(); // данные о пользователе

            // dump($arCurrentUserCustomer);
            ?>

            <!-- Заявка в закрепе -->
            <div class="keys-translr__box-discussion-bshadow mb-40">
                <div class="keys-translr__box-discussion-customer pt-20 pb-20 pl-20 pr-20 color__white fs-16">
                    <span>МФЦ:<?= $arCurrentUserCustomer['NAME'] ?></span> <span><?= $arCurrentUserCustomer['EMAIL'] ?></span> |
                    <span><?= $arResult['TIMESTAMP_X']; ?></span>
                </div>
                <div class="fjc-sb fw-wrap">
                    <div class="fjc-sb pt-20 pb-20 pl-20 pr-20 flex__50">
                        <div class="fjc-sb">
                            <div class="customer-logo pr-40 mb-20">
                                <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/customer.svg" alt="customer"/>
                            </div>
                            <div>
                                <ul class="fs-16">
                                    <li>
                                        <span class="fontw-700">ФИО:</span> <?= $arResult['PROPERTIES']['CUSTOMER_FIO']['VALUE']; ?>
                                    </li>
                                    <li>
                                        <span class="fontw-700">Телефон:</span> <?= $arResult['PROPERTIES']['CUSTOMER_PHONE']['VALUE']; ?>
                                    </li>
                                    <li>
                                        <span class="fontw-700">Комментарий:</span> <?= $arResult['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']; ?>
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
                                                echo '<span class="status-str__success">Выполненная</span>';
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
                                    <ul class="keys-translr__box-discussion-content_files">

                                        <?php

                                        $attachedFilesTranslate = json_decode($arResult['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'], true);

                                        foreach ($attachedFilesTranslate as $key):

                                            $urlFile = CFile::GetPath($key);
                                            $dataFile = CFile::GetByID($key);
                                            $arrDataFile = $dataFile->Fetch();
                                            // dump($arrDataFile);
                                            ?>
                                            <li class="fjc-s fai-e mb-10 mr-10">
                                              <span class="pr-10">
                                                  <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg" alt="file"/>
                                              </span>
                                                <a href="<?= $urlFile; ?>" class="fs-16 color__blue-light"><?= $arrDataFile['ORIGINAL_NAME']; ?></a>
                                            </li>
                                        <?php endforeach; ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex__35">
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
                                        echo '<span class="status-str__success">Выполненная</span>';
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

                            <?php if (!empty($arResult['PROPERTIES']['GENERATED_LINK']['VALUE'])): ?>
                                <div>
                                    <span class="fontw-700">Ссылка:</span>
                                    <a class="keys-translr__box-discussion-content_transfer-link" href="/transfer-view/?hash=05512f70d2dd5b9538ba5b3f22b33993">Страница с переводом</a>
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
                                    <span>Агентство переводов ООО «Ключи»:</span>
                                    <span>klych_user@info.ru</span> |
                                    <span>25.05.2021 13:07:49</span>
                                </div>
                                <div class="fjc-sb fw-wrap">
                                    <div class="fjc-sb pt-20 pb-20 pl-20 pr-20 flex__50">
                                        <div class="executor-logo pr-40 mb-20">
                                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/executor.svg" alt="executor"/>
                                        </div>
                                        <div>
                                            <div class="color__black fs-16">
                                                Lorem ipsum dolor sit amet consectetur, adipisicing
                                                elit. Maiores nobis, dignissimos aspernatur
                                                assumenda aliquam in alias harum, perferendis labore
                                                porro sint fugiat qui incidunt. Dolore corporis
                                                laudantium nesciunt natus reiciendis?
                                            </div>
                                            <div class="mt-20 mb-20">
                                                <ul class="keys-translr__box-discussion-content_files">
                                                    <li class="fjc-s fai-e mb-10 mr-10">
                                                <span class="pr-10">
                                                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg" alt="file"/>
                                                </span>
                                                        <a href="#!" class="fs-16 color__blue-light">file.pdf</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fjc-sb flex__35">
                                        <div class="fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
                                            <div>
                                                <span class="fontw-700">Статус:</span>
                                                <span class="status-str status-str__primary">Выполненная</span>
                                            </div>
                                            <div>
                                                <span class="fontw-700">Ссылка:</span>
                                                <a class="keys-translr__box-discussion-content_transfer-link" href="/transfer-view/?hash=05512f70d2dd5b9538ba5b3f22b33993">
                                                    страница перевода
                                                </a>
                                            </div>
                                        </div>
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
                                    <span>МФЦ:</span> <span>mail@mail.ru</span> |
                                    <span>21.01.2021 13:05:56</span>
                                </div>
                                <div class="fjc-sb fw-wrap">
                                    <div class="fjc-sb pt-20 pb-20 pl-20 pr-20 flex__50">
                                        <div class="customer-logo pr-40 mb-20">
                                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/customer.svg" alt="customer"/>
                                        </div>
                                        <div>
                                            <div class="color__black fs-16">
                                                Lorem ipsum dolor sit amet consectetur, adipisicing
                                                elit. Maiores nobis, dignissimos aspernatur
                                                assumenda aliquam in alias harum, perferendis labore
                                                porro sint fugiat qui incidunt. Dolore corporis
                                                laudantium nesciunt natus reiciendis?
                                            </div>
                                            <div class="mt-20 mb-20">
                                                <ul class="keys-translr__box-discussion-content_files">
                                                    <li class="fjc-s fai-e mb-10 mr-10">
                                                <span class="pr-10">
                                                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg" alt="file"/>
                                                </span>
                                                        <a href="#!" class="fs-16 color__blue-light">file.pdf</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex__35">
                                        <div class=" fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
                                            <div>
                                                <span class="fontw-700">Статус:</span>
                                                <span class="status-str status-str__warning">Ошибка в переводе</span>
                                            </div>
                                        </div>
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

                    $findGroupKeys = in_array("5", $arGroups);
                    // dump($findGroupKeys);

                ?>

                <?php
                // Если переводчик
                if($findGroupKeys): ?>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/form/translator/index.php"
                        )
                    );?>

                <?php  else: ?>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/form/customer/index.php"
                        )
                    );?>

                <?php  endif; ?>

            </div>
            <!-- Форма end -->
        </div>

    </div>
</div>
