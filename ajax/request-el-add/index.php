<?php
 require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include.php");
/*
 * Метод создания новой заявки
 *
 * $_POST['title']; str
 * $_POST['fio']; str
 * $_POST['phone']; str
 * $_POST['comment']; str
 * $_POST['ready_documents']; str
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

if(!empty($_FILES['upfiles']['name'][0])){
    $arrParam[] = count($_FILES['upfiles']['name']);
    $arrParam[] = $_POST['userId'];

    $methodAction = "Methods::" . $_POST['action'];
    $callback = call_user_func_array($methodAction, $arrParam);

    if($callback){
        $success = json_encode(['reqId' => $callback, 'countFiles' => count($_FILES['upfiles']['name'])]);
        echo $success;
    }else {
        // Валидация, если нету загруженных файлов
        $countFiles = json_encode(['reqId' => 0, 'countFiles' => 0]);
        echo $countFiles;
    }
}else {
    // Валидация, если нету загруженных файлов
    $countFiles = json_encode(['reqId' => 0, 'countFiles' => 0]);
    echo $countFiles;
}

?>
