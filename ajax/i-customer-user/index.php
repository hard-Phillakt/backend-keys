<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php"); ?>


<?php if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false): ?>

    <?php

    if (CModule::IncludeModule("iblock")) {
        $iterator = CIBlockElement::GetPropertyValues(1, array('ACTIVE' => 'Y', 'ID' => $_POST['request-id']), false, []);
        while ($row = $iterator->Fetch()) {
            $arUsers = json_decode($row[1]);
            $arUsers[] = (int)$_POST['i-customer-user'];

            $ELEMENT_ID = $_POST['request-id'];
            $PROPERTY_CODE = "CUSTOMER_ID";
            $PROPERTY_VALUE = json_encode($arUsers, true);

            // Установим новое значение для данного свойства данного элемента
            CIBlockElement::SetPropertyValuesEx($_POST['request-id'], false, array($PROPERTY_CODE => $PROPERTY_VALUE));
            $responseJson = json_encode(['el' => $ELEMENT_ID, 'users' => $PROPERTY_VALUE]);
            echo $responseJson;
        }
    }
    ?>

<?php else: ?>

    <?= 'Мы здесь таких не любим!'; ?>

<?php endif; ?>