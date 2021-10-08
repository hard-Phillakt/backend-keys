<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 02.07.2021
 * Time: 17:28
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php"); ?>


<?php if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false): ?>

    <?php if (CModule::IncludeModule("iblock")) {


        $post_data_json = json_decode($_POST['data'], true);

        // Инфоблок "Заявки"
        $_elRequests = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => 1], false, false, []);
        while ($_elRequestsObj = $_elRequests->GetNextElement()) {
            $elRequestsFields = $_elRequestsObj->GetFields();

            if($elRequestsFields['ID'] == $post_data_json['ID']){
                $requestsArr[$elRequestsFields['ID']]['ELEMENT'] = $elRequestsFields;

                $_elRequestsProp = CIBlockElement::GetProperty(1, $post_data_json['ID'], [], []);
                while ($elRequestsPropFields = $_elRequestsProp->Fetch()) {
                    $requestsArr[$elRequestsFields['ID']]['ELEMENT']['PROP'][$elRequestsPropFields['CODE']] = $elRequestsPropFields;
                }
            }
        }

        // Инфоблок "Заявки" end


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
            if ($key['ELEMENT']['PROP']['ID_REQUEST']['VALUE'] != $post_data_json['ID']) {
                // удаляем сообщения не относящиеся к заявке
                unset($discussionsArr[$key['ELEMENT']['ID']]);
            } else {
                $discussionsArrSort[$key['ELEMENT']['PROP']['ID_MESSAGE_QUEUES_FOR_THE_TICKET']['VALUE']] = $key['ELEMENT'];
            }
        }

        ksort($discussionsArrSort, SORT_NUMERIC);

        // Вывод обсуждений к текущей заявке end
    } ?>

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
                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/executor.svg" alt="executor"/>
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
                                                <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/file.svg" alt="file"/>
                                            </span>
                                            <a href="<?= $urlFile; ?>" class="fs-16 color__blue-light"><?= $arrDataFile['ORIGINAL_NAME']; ?></a>
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
                                            echo '<span class="fontw-700">Статус: </span>';
                                            echo '<span class="status-str status-str__danger">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                            break;
                                        case 13:
                                            echo '<span class="fontw-700">Статус: </span>';
                                            echo '<span class="status-str status-str__success">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                            break;
                                        case 14:
                                            echo '<span class="fontw-700">Статус: </span>';
                                            echo '<span class="status-str status-str__warning">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                            break;
                                        case 15:
                                            echo '<span class="fontw-700">Статус: </span>';
                                            echo '<span class="status-str status-str__default">' . $key["PROP"]["STATUS"]["VALUE_ENUM"] . '</span>';
                                            break;
                                    }

                                    ?>


                                </div>

                                <?php if ($key["PROP"]["STATUS"]["VALUE"] == 13): ?>
                                    <div>
                                        <span class="fontw-700">Стоимость:</span>

                                        <span class="status-str status-str__warning"><?= $requestsArr[$post_data_json['ID']]['ELEMENT']['PROP']['FIELD_PRICE']['VALUE']; ?> ₽</span>
                                    </div>

                                    <div>
                                        <span class="fontw-700">Ссылка:</span>
                                        <a class="keys-translr__box-discussion-content_transfer-link" href="/transfer-view/?hash=<?= $requestsArr[$post_data_json['ID']]['ELEMENT']['CODE']; ?>">
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
                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/customer.svg" alt="customer"/>
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
                                        ?>

                                        <li class="fjc-s fai-e mb-10 mr-30">
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

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>