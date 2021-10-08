<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php"); ?>


<?php if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false): ?>

    <?php

    if (CModule::IncludeModule("iblock")) {

        $iterator = CIBlockElement::GetPropertyValues(1, array('ACTIVE' => 'Y', 'ID' => $_POST['req-id']), false, []);
        while ($row = $iterator->Fetch()) {

            $arUsers = json_decode($row[1]);
            $resSearchId = array_search($_POST['user-id'], $arUsers);
            unset($arUsers[$resSearchId]);

            $ELEMENT_ID = $_POST['req-id'];
            $PROPERTY_CODE = "CUSTOMER_ID";
            $PROPERTY_VALUE = json_encode(array_values($arUsers), true);

            // Установим новое значение для данного свойства данного элемента
            CIBlockElement::SetPropertyValuesEx($_POST['req-id'], false, array($PROPERTY_CODE => $PROPERTY_VALUE));
            $responseJson = json_encode(['el' => $ELEMENT_ID, 'users' => $PROPERTY_VALUE]);
             echo $responseJson;
        }
    }
    ?>

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>