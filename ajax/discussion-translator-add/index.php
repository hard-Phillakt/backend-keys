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

//  dump($_POST);

//  dump($_FILES);


if (strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false) {
    $arrParam[] = 1;
    $arrParam[] = $USER->GetID();
    $arrParam[] = $_POST['status'];
    $arrParam[] = $_POST['idRequest'];
    $arrParam[] = $_POST['createLink'];
    $arrParam[] = strtotime($_POST['startLiveLinks']);
    $arrParam[] = strtotime($_POST['endLiveLinks']);

    $methodAction = "Methods::" . $_POST['action'];

    if (empty(strtotime($_POST['startLiveLinks'])) && empty(strtotime($_POST['endLiveLinks'])) && empty($_FILES['upfiles']['name'][0]) && count($_FILES['upfiles']['name']) == 1) {
        // Если простое сообщение без генерации временной ссылки
        $callback = call_user_func_array($methodAction, $arrParam);
        $res = json_encode(['status_code' => 1]);
        echo $res;
    }elseif (!empty(strtotime($_POST['startLiveLinks'])) && !empty(strtotime($_POST['endLiveLinks'])) && !empty($_FILES['upfiles']['name'][0]) && count($_FILES['upfiles']['name']) == 1){
        // Если сообщение с генерацией временной ссылки и с одним файлом
        $callback = call_user_func_array($methodAction, $arrParam);
        $res = json_encode(['status_code' => 2]);
        echo $res;
    }elseif (!empty(strtotime($_POST['startLiveLinks'])) && !empty(strtotime($_POST['endLiveLinks'])) && !empty($_FILES['upfiles']['name'][0]) && count($_FILES['upfiles']['name']) > 1){
        // Не больше одного файла
        $res = json_encode(['status_code' => 3]);
        echo $res;
    }elseif (!empty(strtotime($_POST['startLiveLinks'])) && !empty(strtotime($_POST['endLiveLinks'])) && empty($_FILES['upfiles']['name'][0])){
        // Не возможно создать ссылку без файлов .PDF
        $res = json_encode(['status_code' => 4]);
        echo $res;
    }

} else {
    echo 'Мы здесь таких не любим!';
}
