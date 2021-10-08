<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 02.07.2021
 * Time: 17:28
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php"); ?>


<?php if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false && $_POST['deactivate-request'] == 1):

    if (CModule::IncludeModule("iblock")) {
        // Инфоблок "Заявки"
        $_elRequests = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => 1], false, false, []);
        while ($_elRequestsObj = $_elRequests->GetNextElement()) {
            $elRequestsFields = $_elRequestsObj->GetFields();

            $requestsArr[$elRequestsFields['ID']]['ELEMENT'] = $elRequestsFields;

            $_elRequestsProp = CIBlockElement::GetProperty(1, $elRequestsFields['ID'], [], []);
            while ($elRequestsPropFields = $_elRequestsProp->Fetch()) {
                $requestsArr[$elRequestsFields['ID']]['ELEMENT']['PROP'][$elRequestsPropFields['CODE']] = $elRequestsPropFields;
            }
        }

        // Инфоблок "Заявки" end

        foreach ($requestsArr as $key) {

            $arrLifeDate = json_decode($key['ELEMENT']['PROP']['LINK_LIFE_DATE']['VALUE'], true);
            $getDateNow = getdate()[0];

            $el = new CIBlockElement();
            if ($key['ELEMENT']['ID'] == '84') {
                if ($arrLifeDate[1] < $getDateNow) {

                    // Деактивация заявки
                    $res = $el->Update($key['ELEMENT']['ID'], ['ACTIVE' => 'N']);
                    // Деактивация заявки end

                    // Удаление заявки спустя 1 месяц
                    $tamiDeleteRequest = $arrLifeDate[1] + 2629743; // 1 месяц (30.44 дней)  - 2629743
                    if ($tamiDeleteRequest < $getDateNow) {
                        $attFilesForTransl = json_decode($key['ELEMENT']['PROP']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'], true);
                        $attFilesWithTransl = json_decode($key['ELEMENT']['PROP']['ATTACHED_FILES_WITH_TRANSLATE']['VALUE'], true);
                        $arrFilesForDel = array_merge($attFilesForTransl, $attFilesWithTransl);

                        foreach ($attFilesForTransl as $keyAttFFT) {
                            $getParam = CFile::GetByID($keyAttFFT);
                            CFile::Delete($keyAttFFT); // Удаление заявки
                            $subDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $getParam->arResult[0]['SUBDIR'];
                            rmdir($subDir); // Удаление директории
                        }
                        if ($el::Delete($key['ELEMENT']['ID'])) {
                            echo "Удалена заявка №" . $key['ELEMENT']['ID'];
                        }
                    }
                    // Удаление заявки спустя 1 месяц end
                }
            }
        }
    }

    ?>

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>