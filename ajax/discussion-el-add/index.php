<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");

/*
 * Метод создания нового сообщения в инфоблоке "Обсуждение"
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

// dump(strtotime('05/19/2021'));

$arrParam[] = count($_FILES['upfiles']['name']);
$arrParam[] = $USER->GetID();
$arrParam[] = $_POST['status'];
$arrParam[] = $_POST['idRequest'];
$arrParam[] = $_POST['createLink'];
$arrParam[] = strtotime($_POST['startLiveLinks']);
$arrParam[] = strtotime($_POST['endLiveLinks']);


dump($arrParam);


$methodAction = "Methods::" . $_POST['action'];

call_user_func_array($methodAction, $arrParam);



