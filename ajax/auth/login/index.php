<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");

global $USER;

if(isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password'])){

    $USER = new CUser;
    $arAuthResult = $USER->Login($_POST['login'], $_POST['password'], "Y");
    $result = $APPLICATION->arAuthResult = $arAuthResult;

    if($result['TYPE'] !== 'ERROR'){
        echo true;
    }else {
        echo false;
    }
}else {
    echo false;
}
