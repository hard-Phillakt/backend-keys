<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");

//echo $_POST['logout'];

if(!empty($_POST['logout'])){
    $USER->Logout();
    echo true;
}else {
    echo false;
}

?>


