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

// dump($_POST);

// dump($_FILES);

// var_dump($_POST);

// var_dump($_FILES);

if(strpos($_SERVER['HTTP_REFERER'], COMBAT_DOMAIN) != false) {
    $arrParam[] = 1;
    $arrParam[] = $USER->GetID();
    $arrParam[] = $_POST['status'];
    $arrParam[] = $_POST['idRequest'];
    $arrParam[] = $_POST['createLink'];
    $arrParam[] = strtotime($_POST['startLiveLinks']);
    $arrParam[] = strtotime($_POST['endLiveLinks']);

    $methodAction = "Methods::" . $_POST['action'];

    if(empty($arrParam[5]) && empty($arrParam[6])){
        $callback = call_user_func_array($methodAction, $arrParam);
        // Если простое сообщение без генерации временной ссылки
        $success = json_encode(['reqId' => $callback, 'countFiles' => 0]);
        echo $success;
    }elseif (!empty($arrParam[5]) && !empty($arrParam[6]) && !empty($_FILES['upfiles']['name'])){

        if(count($_FILES['upfiles']['name']) < 2){
            $callback = call_user_func_array($methodAction, $arrParam);
            // Если сообщение с генерацией временной ссылки и с одним файлом
            $success = json_encode(['reqId' => $callback, 'createLink' => 1]);
            echo $success;
        }else {
            $success = json_encode(['reqId' => null, 'oneFiles' => null]);
            echo $success;
        }

    }elseif (!empty($arrParam[5]) && !empty($arrParam[6]) && empty($_FILES['upfiles']['name'])){
        // Если сообщение с генерацией временной ссылки но без прикрепленных файлов. Вывод ошибки!
        $success = json_encode(['reqId' => null, 'countFiles' => 0, 'createLink' => 0]);
        echo $success;
    }
}else {
    echo 'Мы здесь таких не любим!';
}
