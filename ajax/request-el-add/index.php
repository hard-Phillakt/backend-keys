<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");

/*
 * Метод создания новой заявки
 *
 * $_POST['title']; str
 * $_POST['fio']; str
 * $_POST['phone']; str
 * $_POST['comment']; str
 *
 * $_FILES['upfiles']['name'] // arr
 * $_FILES['upfiles']['type'] // arr
 * $_FILES['upfiles']['tmp_name'] // arr
 * $_FILES['upfiles']['error'] // arr
 * $_FILES['upfiles']['size'] // arr
 *
 * */

dump($_POST);

dump($_FILES);

$arrParam[] = count($_FILES['upfiles']['name']);
$arrParam[] = $USER->GetID();

$methodAction = "Methods::" . $_POST['action'];

call_user_func_array($methodAction, $arrParam);



