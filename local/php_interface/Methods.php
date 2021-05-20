<?php

use src\models\App;

class Methods
{
    // Свойство хранит id "Заявки"
    public static $requestId;

    // Свойство хранит id "Обсуждения"
    public static $discussionId;

    // Метод создает новую заявку
    static public function addElementRequest($countFiles = null, $userId = null)
    {

        if (CModule::IncludeModule("iblock")) {
            $i = 0;
            while ($i < $countFiles) {

                $arFiles = [
                    "name" => $_FILES['upfiles']['name'][$i],
                    "size" => $_FILES['upfiles']['size'][$i],
                    "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                    "type" => $_FILES['upfiles']['type'][$i],
                ];

                $el = new CIBlockElement;

                if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

                    if ($fid) {

                        $arrPropIdsFiles[] = $fid;
                        $jsonE = json_encode($arrPropIdsFiles);

                        $PROP = [];
                        $PROP[1] = $userId;
                        $PROP[2] = $_POST['fio'];
                        $PROP[3] = $_POST['phone'];
                        $PROP[4] = trim($_POST['comment']);
                        $PROP[5] = 6; // "В работе"
                        $PROP[6] = $jsonE;

                        $arLoadProductArray = [
                            "MODIFIED_BY" => $userId,
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => 1,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $_POST['title'],
                            "ACTIVE" => "Y",
                        ];

                        if (empty(self::$requestId)) {
                            $requestId = $el->Add($arLoadProductArray);
                            self::$requestId = $requestId;

                        } else {
                            if (!empty(self::$requestId)) {
                                $requestId = $el->Update(self::$requestId, $arLoadProductArray);
                            }
                        }

                        // Выводим успех
                        // echo "id: ". $prodId . "<br>";
                    }

                } else {
                    // Выводим ошибку
                    //echo "Error: ".$el->LAST_ERROR;
                }

                $i++;
            }
        }
    }

    // Метод создает новое сообщение для заявки от лица "Заказчика"
    static public function addElementDiscussionCustomer($countFiles = null, $userId = null, $createLink = false, $startLiveLinks = null, $endLiveLinks = null, $status = null, $idRequest = null)
    {
        if (CModule::IncludeModule("iblock")) {


            // Для менеджера (Переводчика)
            if ($startLiveLinks) {
                $i = 0;
                while ($i < $countFiles) {

                    $arFiles = [
                        "name" => $_FILES['upfiles']['name'][$i],
                        "size" => $_FILES['upfiles']['size'][$i],
                        "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                        "type" => $_FILES['upfiles']['type'][$i],
                    ];

                    $el = new CIBlockElement;

                    if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

//                        $arrPropIdsFilesRequest[] = $fid;
//                        $jsonE = json_encode($arrPropIdsFilesRequest);
//
                        $PROP = [];
//                        $PROP[1] = $userId;
//                        $PROP[2] =  $_POST['fio'];
//                        $PROP[3] = $_POST['phone'];
//                        $PROP[4] = trim($_POST['comment']);
//                        $PROP[5] = 6; // "В работе"
//                        $PROP[6] = $jsonE;

                        // Если сообщение без сформированной ссылки

                        $arrPropIdsFilesDiscussion[] = $fid;
                        $jsonEncodeDiscussion = json_encode($arrPropIdsFilesDiscussion);

                        $PROP[10] = $userId; // ID пользователя
                        $PROP[13] = $_POST['id-request']; // ID заявки
                        $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                        $PROP[12] = $_POST['status']; // Статус к сообщению
                        $PROP[11] = $jsonEncodeDiscussion; // ID очереди сообщения к заявке (временная метка)


                        // Данные для инфоблока "Заявки" id - 1
//                        $arLoadProductArray = [
//                            "MODIFIED_BY"    => $userId,
//                            "IBLOCK_SECTION_ID" => false,
//                            "IBLOCK_ID"      => 1,
//                            "PROPERTY_VALUES"=> $PROP,
//                            "NAME"           => $_POST['title'],
//                            "ACTIVE"         => "Y",
//                        ];

                        // Данные для инфоблока "Обсуждение" id - 2
                        $arLoadDiscussionArray = [
                            "MODIFIED_BY" => $userId,
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => 2,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $_POST['comment'],
                            "ACTIVE" => "Y",
                        ];

//                        dump($arLoadDiscussionArray);die;

                        if (empty(self::$discussionId)) {
                            $discussionId = $el->Add($arLoadDiscussionArray);
                            self::$discussionId = $discussionId;

                        } else {
                            if (!empty(self::$discussionId)) {
                                $discussionId = $el->Update(self::$discussionId, $arLoadDiscussionArray);
                            }
                        }

                        // Выводим id обсуждения
                        echo "id: " . $discussionId . "<br>";

                    } else {
                        // Выводим ошибку
                        echo "Error: " . $el->LAST_ERROR;
                    }

                    $i++;
                }
            } else {
                $i = 0;
                while ($i < $countFiles) {

                    $arFiles = [
                        "name" => $_FILES['upfiles']['name'][$i],
                        "size" => $_FILES['upfiles']['size'][$i],
                        "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                        "type" => $_FILES['upfiles']['type'][$i],
                    ];

                    $el = new CIBlockElement;

                    if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

//                        $arrPropIdsFilesRequest[] = $fid;
//                        $jsonE = json_encode($arrPropIdsFilesRequest);
//
                        $PROP = [];
//                        $PROP[1] = $userId;
//                        $PROP[2] =  $_POST['fio'];
//                        $PROP[3] = $_POST['phone'];
//                        $PROP[4] = trim($_POST['comment']);
//                        $PROP[5] = 6; // "В работе"
//                        $PROP[6] = $jsonE;

                        // Если сообщение без сформированной ссылки
                        if (!$createLink) {
                            $arrPropIdsFilesDiscussion[] = $fid;
                            $jsonEncodeDiscussion = json_encode($arrPropIdsFilesDiscussion);

                            $PROP[10] = $userId; // ID пользователя
                            $PROP[13] = $_POST['id-request']; // ID заявки
                            $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                            $PROP[12] = $_POST['status']; // Статус к сообщению
                            $PROP[11] = $jsonEncodeDiscussion; // ID очереди сообщения к заявке (временная метка)


                            // Данные для инфоблока "Заявки" id - 1
//                        $arLoadProductArray = [
//                            "MODIFIED_BY"    => $userId,
//                            "IBLOCK_SECTION_ID" => false,
//                            "IBLOCK_ID"      => 1,
//                            "PROPERTY_VALUES"=> $PROP,
//                            "NAME"           => $_POST['title'],
//                            "ACTIVE"         => "Y",
//                        ];

                            // Данные для инфоблока "Обсуждение" id - 2
                            $arLoadDiscussionArray = [
                                "MODIFIED_BY" => $userId,
                                "IBLOCK_SECTION_ID" => false,
                                "IBLOCK_ID" => 2,
                                "PROPERTY_VALUES" => $PROP,
                                "NAME" => $_POST['comment'],
                                "ACTIVE" => "Y",
                            ];

//                        dump($arLoadDiscussionArray);die;

                            if ($i < 1) {
                                $prodId = $el->Add($arLoadDiscussionArray);
                                self::$prodId = $prodId;

                            } else {
                                if (!empty(self::$prodId)) {
                                    $prodId = $el->Update(self::$prodId, $arLoadDiscussionArray);
                                }
                            }

                            // Выводим успех
                            echo "id: " . $prodId . "<br>";
                        }


                    } else {
                        // Выводим ошибку
                        echo "Error: " . $el->LAST_ERROR;
                    }

                    $i++;
                }
            }

        }
    }

    static public function updateElementRequestAddLinks($countFiles = null, $userId = null, $idRequest = null, $startLiveLinks = null, $endLiveLinks = null)
    {

        $propElementArr = [];

        $_elElement = CIBlockElement::GetList(["SORT" => "ASC"], ['ID' => $idRequest], false, false, []);

        while ($_elElementObj = $_elElement->GetNextElement()) {
            $elElementFields = $_elElementObj->GetFields();

            $propElementArr['ELEMENT'] = $elElementFields;

            $_elElementProp = CIBlockElement::GetProperty(1, $idRequest, [], []);

            while ($elElementPropFields = $_elElementProp->Fetch()) {

                $propElementArr['PROPERTIES'][$elElementPropFields['CODE']] = $elElementPropFields;

            }

        }

        dump($propElementArr);

        if (!empty($propElementArr)) {

            $i = 0;
            while ($i < $countFiles) {

                $arFiles = [
                    "name" => $_FILES['upfiles']['name'][$i],
                    "size" => $_FILES['upfiles']['size'][$i],
                    "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                    "type" => $_FILES['upfiles']['type'][$i],
                ];

                $el = new CIBlockElement;

                if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

                    $arrPropIdsFilesRequest[] = $fid;
                    $jsonE = json_encode($arrPropIdsFilesRequest);

                    $PROP = [];

                    // первоначальные параметры
                    $PROP[1] = $propElementArr['PROPERTIES']['CUSTOMER_ID']['VALUE'];
                    $PROP[2] = $propElementArr['PROPERTIES']['CUSTOMER_FIO']['VALUE'];
                    $PROP[3] = $propElementArr['PROPERTIES']['CUSTOMER_PHONE']['VALUE'];
                    $PROP[4] = trim($propElementArr['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']);
                    $PROP[6] = $propElementArr['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'];
                    // первоначальные параметры end

                    $PROP[7] = json_encode([$startLiveLinks, $endLiveLinks]); // временные точки для ссылки
                    $PROP[15] = $jsonE; // файлы с готовым переводом
                    $PROP[8] = "/transfer-view/?ELEMENT_ID=" . $idRequest;
                    $PROP[9] = $_POST['price'];
                    $PROP[5] = 8; // заявка "Выполненная"
                    $PROP[16] = trim($_POST['comment']); // Текст перевода
                    $PROP[17] = $userId; // id переводчика

                    // Данные для инфоблока "Заявки" id - 1
                    $arLoadProductArray = [
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => 1,
                        "PROPERTY_VALUES" => $PROP,
                        "ACTIVE" => "Y",
                    ];

                    $discussion = $el->Update($idRequest, $arLoadProductArray);

                    // Выводим id обсуждения
                    echo "id: " . $discussion . "<br>";

                } else {
                    // Выводим ошибку
                    echo "Error: " . $el->LAST_ERROR;
                }

                $i++;
            }

        }
    }

    // Метод создает новое сообщение для заявки от лица "Переводчика"
    static public function addElementDiscussionTranslator($countFiles = null, $userId = null, $status = null, $idRequest = null, $createLink = false, $startLiveLinks = null, $endLiveLinks = null)
    {

        if (CModule::IncludeModule("iblock")) {


            // Если нужно сформировать ссылку для скачивания и время доступа к ней
            if (isset($createLink) && !empty($createLink) && !empty($startLiveLinks) && !empty($endLiveLinks)) {

                self::updateElementRequestAddLinks($countFiles, $userId, $idRequest, $startLiveLinks, $endLiveLinks);

            } else {
                // Для менеджера (Переводчика)
                $i = 0;
                while ($i < $countFiles) {

                    $arFiles = [
                        "name" => $_FILES['upfiles']['name'][$i],
                        "size" => $_FILES['upfiles']['size'][$i],
                        "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                        "type" => $_FILES['upfiles']['type'][$i],
                    ];

                    $el = new CIBlockElement;

                    if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

                        $PROP = [];

                        $arrPropIdsFilesDiscussion[] = $fid;
                        $jsonEncodeDiscussion = json_encode($arrPropIdsFilesDiscussion);

                        $PROP[10] = $userId; // ID пользователя
                        $PROP[13] = $idRequest; // ID заявки
                        $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                        $PROP[12] = $status; // Статус к сообщению
                        $PROP[11] = $jsonEncodeDiscussion; // ID очереди сообщения к заявке (временная метка)

                        // Данные для инфоблока "Обсуждение" id - 2
                        $arLoadDiscussionArray = [
                            "MODIFIED_BY" => $userId,
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => 2,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $_POST['title'],
                            "ACTIVE" => "Y",
                        ];

                        if (empty(self::$discussionId)) {
                            $discussionId = $el->Add($arLoadDiscussionArray);
                            self::$discussionId = $discussionId;
                        } else {
                            if (!empty(self::$discussionId)) {
                                $discussionId = $el->Update(self::$discussionId, $arLoadDiscussionArray);
                            }
                        }

                        // Выводим id обсуждения
                        //echo "id: " . $discussionId . "<br>";

                    } else {
                        // Выводим ошибку
                        //echo "Error: " . $el->LAST_ERROR;
                    }

                    $i++;
                }
            }
        }
    }
}
