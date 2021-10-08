<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 06.07.2021
 * Time: 17:47
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php"); ?>


<?php if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false):?>

<?php if (CModule::IncludeModule("iblock")):
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

    ?>


    <div class="fjc-s ffd-column fw-wrap pt-20 pb-20 pl-20 pr-20 fs-16">
        <div>
            <span class="fontw-700">Статус:</span>

            <?php

            switch ($requestsArr[$post_data_json['ID']]['ELEMENT']['PROP']['STATUS']['VALUE']) {

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

        if (!empty($requestsArr[$post_data_json['ID']]['ELEMENT']['CODE']) && $requestsArr[$post_data_json['ID']]['ELEMENT']["PROP"]["STATUS"]["VALUE"] == 8 || !empty($requestsArr[$post_data_json['ID']]['ELEMENT']['CODE']) && $requestsArr[$post_data_json['ID']]['ELEMENT']["PROP"]["STATUS"]["VALUE"] == 10): ?>
            <div>
                <div>
                    <span class="fontw-700">Время действия ссылки:</span>

                    <div>
                        <?php $strToArrLinkLife = json_decode($requestsArr[$post_data_json['ID']]['ELEMENT']['PROP']['LINK_LIFE_DATE']['VALUE'], true); ?>
                        <span>с </span> <?= date("d-m-Y h:i:s", $strToArrLinkLife[0]); ?>
                    </div>

                    <div>
                        <span>по </span> <?= date("d-m-Y h:i:s", $strToArrLinkLife[1]); ?>
                    </div>

                </div>
            </div>
            <div>
                <span class="fontw-700">Ссылка:</span>
                <a class="keys-translr__box-discussion-content_transfer-link" href="/transfer-view/?hash=<?= $requestsArr[$post_data_json['ID']]['ELEMENT']['CODE']; ?>">Страница с переводом</a>
            </div>
        <?php endif; ?>

    </div>

    <?php endif; ?>

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>