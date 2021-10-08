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

                        $arrUsers[] = (int) $userId;
                        $jsonUser = json_encode($arrUsers);

                        $PROP = [];
                        $PROP[1] = $jsonUser;
                        $PROP[2] = $_POST['fio'];
                        $PROP[3] = $_POST['phone'];
                        $PROP[4] = trim($_POST['comment']);
                        $PROP[5] = $_POST['status']; // "В работе id - 6, Срочная id - 7"
                        $PROP[6] = $jsonE;
                        $PROP[22] = $_POST['ready_documents'];

                        $arLoadProductArray = [
                            "MODIFIED_BY" => $userId,
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => 1,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $_POST['title'],
                            //"CODE" => md5('klyuchi-hash' . $userId . strtotime("now")),
                            "CODE" => substr(str_shuffle(PERMITTED_CHARS), 0, 200),
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
                        // return $requestId;
                    }

                } else {
                    // Выводим ошибку
                    // echo false;
                }

                $i++;
            }

            return self::$requestId;
        }
    }


    // Метод создает новое сообщение для заявки от лица "Заказчика"
    static public function addElementDiscussionCustomer($countFiles = null, $userId = null, $status = null, $idRequest = null)
    {
        if (CModule::IncludeModule("iblock")) {

            // Для менеджера (Заказчика)
            $i = 0;
            while ($i < $countFiles) {

                $arFiles = [
                    "name" => $_FILES['upfiles']['name'][$i],
                    "size" => $_FILES['upfiles']['size'][$i],
                    "tmp_name" => $_FILES['upfiles']['tmp_name'][$i],
                    "type" => $_FILES['upfiles']['type'][$i],
                ];

                $el = new CIBlockElement;

                //##################### Если есть прикрепленные файлы для перевода
                if ($fid = CFile::SaveFile($arFiles, "uploads_work_files")) {

                    $PROP = [];

                    $arrPropIdsFilesDiscussion[] = $fid;
                    $jsonEncodeDiscussion = json_encode($arrPropIdsFilesDiscussion);

                    $arrPropIdGroupUser = [];
                    $idGroupUser = json_decode($_POST['idGroupUser'], true);
                    for($iIdGroupUser = 0; $iIdGroupUser < count($idGroupUser); $iIdGroupUser++){
                        $arrPropIdGroupUser[] = (int) $idGroupUser[$iIdGroupUser];
                    }

                    $PROP[10] = $userId; // ID пользователя
                    $PROP[13] = $idRequest; // ID заявки
                    $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                    $PROP[12] = $status; // Статус к сообщению
                    $PROP[11] = $jsonEncodeDiscussion; // ID очереди сообщения к заявке (временная метка)
                    $PROP[19] = json_encode($arrPropIdGroupUser); // Группа пользователя
                    $PROP[20] = trim($_POST['comment']); // Коментарий

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

                        // обновляем статус заявки после создания сообщения
                        self::updateElementRequestStatus($status, $idRequest);
                    } else {
                        if (!empty(self::$discussionId)) {
                            $discussionId = $el->Update(self::$discussionId, $arLoadDiscussionArray);

                            // обновляем статус заявки после создания сообщения
                            self::updateElementRequestStatus($status, $idRequest);
                        }
                    }

                    // Выводим id обсуждения
                    // echo "discussion id: {$discussionId}";

                }

                //################# Если нету прикрепленных файлов для перевода
                else {

                    $PROP = [];

                    $arrPropIdGroupUser = [];
                    $idGroupUser = json_decode($_POST['idGroupUser'], true);
                    for($iIdGroupUser = 0; $iIdGroupUser < count($idGroupUser); $iIdGroupUser++){
                        $arrPropIdGroupUser[] = (int) $idGroupUser[$iIdGroupUser];
                    }

                    $PROP[10] = $userId; // ID пользователя
                    $PROP[13] = $idRequest; // ID заявки
                    $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                    $PROP[12] = $status; // Статус к сообщению
                    //$PROP[11] = $jsonEncodeDiscussion; // id Прикрепленных файлов
                    $PROP[19] = json_encode($arrPropIdGroupUser); // Группа пользователя
                    $PROP[20] = trim($_POST['comment']); // Коментарий

                    // Данные для инфоблока "Обсуждение" id - 2
                    $arLoadDiscussionArray = [
                        "MODIFIED_BY" => $userId,
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => 2,
                        "PROPERTY_VALUES" => $PROP,
                        "NAME" => $_POST['title'],
                        "ACTIVE" => "Y",
                    ];

                    $discussionId = $el->Add($arLoadDiscussionArray);

                    // обновляем статус заявки после создания сообщения
                    self::updateElementRequestStatus($status, $idRequest);

                    // Выводим id обсуждения
                    // echo "discussion id: {$discussionId}";
                    return $discussionId;
                }

                $i++;
            }

            return self::$discussionId;
        }
    }

    // Метод обновляет статус заявки от лица "Заказчика" при создании нового сообщения
    static public function updateElementRequestStatus($status = null, $idRequest = null)
    {
        $el = new CIBlockElement;

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

        // dump($propElementArr);

        if (!empty($propElementArr)) {

            $PROP = [];

            // первоначальные параметры созданой заявки
            $PROP[1] = $propElementArr['PROPERTIES']['CUSTOMER_ID']['VALUE'];
            $PROP[2] = $propElementArr['PROPERTIES']['CUSTOMER_FIO']['VALUE'];
            $PROP[3] = $propElementArr['PROPERTIES']['CUSTOMER_PHONE']['VALUE'];
            $PROP[4] = trim($propElementArr['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']);
            $PROP[6] = $propElementArr['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'];
            $PROP[22] = $propElementArr['PROPERTIES']['READY_DOCUMENTS']['VALUE'];
            // первоначальные параметры созданой заявки end

            $PROP[7] = $propElementArr['PROPERTIES']['LINK_LIFE_DATE']['VALUE']; // Начало и конец активности ссылки
            $PROP[15] = $propElementArr['PROPERTIES']['ATTACHED_FILES_WITH_TRANSLATE']['VALUE']; // файлы с готовым переводом
            $PROP[8] = $propElementArr['PROPERTIES']['GENERATED_LINK']['VALUE']; // ссылка с переводом для заказчика
            $PROP[9] = $propElementArr['PROPERTIES']['FIELD_PRICE']['VALUE']; // цена перевода заполненая переводчиком

            switch ($status){
                case 11: // В работе
                     $PROP[5] = 6; // Статус заявки
                    break;
                case 12: // Срочная
                     $PROP[5] = 7; // Статус заявки
                    break;
                case 13: // Выполненная
                     $PROP[5] = 8; // Статус заявки
                    break;
                case 14: // Ошибка в переводе
                     $PROP[5] = 9; // Статус заявки
                    break;
                case 15: // Завершена
                     $PROP[5] = 10; // Статус заявки
                    break;
            }

            $PROP[16] = $propElementArr['PROPERTIES']['TEXT_TRANSLATE']['VALUE']['TEXT']; // Текст с выполненым переводом
            $PROP[17] = $propElementArr['PROPERTIES']['TRANSLATOR_ID']['VALUE']; // user id переводчика

            // Данные для инфоблока "Заявки" id - 1
            $arLoadProductArray = [
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => 1,
                "PROPERTY_VALUES" => $PROP,
                "ACTIVE" => "Y",
            ];

            $discussionId = $el->Update($idRequest, $arLoadProductArray);

        }

    }


    // Метод создает новое сообщение для заявки от лица "Переводчика"
    static public function addElementDiscussionTranslator($countFiles = null, $userId = null, $status = null, $idRequest = null, $createLink = false, $startLiveLinks = null, $endLiveLinks = null)
    {

        if (CModule::IncludeModule("iblock")) {

            // Если нужно сформировать ссылку для скачивания и время доступа к ней
            if (isset($createLink) && !empty($createLink) && !empty($startLiveLinks) && !empty($endLiveLinks)) {

                self::updateElementRequestAddLinks($countFiles, $userId, $idRequest, $startLiveLinks, $endLiveLinks);

                // Для менеджера (Переводчика) при создании ссылки с переводом
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

                        $arrPropIdGroupUser = [];
                        $idGroupUser = json_decode($_POST['idGroupUser'], true);
                        for($iIdGroupUser = 0; $iIdGroupUser < count($idGroupUser); $iIdGroupUser++){
                            $arrPropIdGroupUser[] = (int) $idGroupUser[$iIdGroupUser];
                        }

                        $PROP[10] = $userId; // ID пользователя
                        $PROP[13] = $idRequest; // ID заявки
                        $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                        $PROP[12] = $status; // Статус к сообщению
                        //$PROP[11] = $jsonEncodeDiscussion; // Id Прикрепленных файлов
                        $PROP[19] = json_encode($arrPropIdGroupUser); // Группа пользователя
                        $PROP[20] = trim($_POST['comment']); // Коментарий

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
                        // echo "el id: {$discussionId}";

                    } else {
                        // Выводим ошибку
                        // echo "error: {$el->LAST_ERROR}";
                    }

                    $i++;
                }

                return self::$discussionId;
            }

            else {
                // Для менеджера (Переводчика) создает новое сообщение для заявки без ссылки
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

                        $arrPropIdGroupUser = [];
                        $idGroupUser = json_decode($_POST['idGroupUser'], true);
                        for($iIdGroupUser = 0; $iIdGroupUser < count($idGroupUser); $iIdGroupUser++){
                            $arrPropIdGroupUser[] = (int) $idGroupUser[$iIdGroupUser];
                        }

                        $PROP[10] = $userId; // ID пользователя
                        $PROP[13] = $idRequest; // ID заявки
                        $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                        $PROP[12] = $status; // Статус к сообщению
                        $PROP[11] = $jsonEncodeDiscussion; // Id Прикрепленных файлов
                        $PROP[19] = json_encode($arrPropIdGroupUser); // Группа пользователя
                        $PROP[20] = trim($_POST['comment']); // Коментарий

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
                        // echo "el id: {$discussionId}";

                    }

                    else {

                        $PROP = [];

                        //$arrPropIdsFilesDiscussion[] = $fid;
                        //$jsonEncodeDiscussion = json_encode($arrPropIdsFilesDiscussion);

                        $arrPropIdGroupUser = [];
                        $idGroupUser = json_decode($_POST['idGroupUser'], true);
                        for($iIdGroupUser = 0; $iIdGroupUser < count($idGroupUser); $iIdGroupUser++){
                            $arrPropIdGroupUser[] = (int) $idGroupUser[$iIdGroupUser];
                        }

                        $PROP[10] = $userId; // ID пользователя
                        $PROP[13] = $idRequest; // ID заявки
                        $PROP[14] = getdate()[0]; // ID очереди сообщения к заявке (временная метка)
                        $PROP[12] = $status; // Статус к сообщению
                        //$PROP[11] = $jsonEncodeDiscussion; // Id Прикрепленных файлов
                        $PROP[19] = json_encode($arrPropIdGroupUser); // Группа пользователя
                        $PROP[20] = trim($_POST['comment']); // Коментарий

                        // Данные для инфоблока "Обсуждение" id - 2
                        $arLoadDiscussionArray = [
                            "MODIFIED_BY" => $userId,
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => 2,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $_POST['title'],
                            "ACTIVE" => "Y",
                        ];

                        $discussionId = $el->Add($arLoadDiscussionArray);

                        //echo $discussionId;

                        // Выводим id обсуждения
                        // echo "el id: {$discussionId}";
                    }

                    $i++;
                }

                return $discussionId;
            }
        }
    }

    // Метод создает новое сообщение для заявки от лица "Переводчика" и вносит перевод в заявку (файлы + текст)
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

        // dump($propElementArr);

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

                    // первоначальные параметры созданой заявки
                    $PROP[1] = $propElementArr['PROPERTIES']['CUSTOMER_ID']['VALUE'];
                    $PROP[2] = $propElementArr['PROPERTIES']['CUSTOMER_FIO']['VALUE'];
                    $PROP[3] = $propElementArr['PROPERTIES']['CUSTOMER_PHONE']['VALUE'];
                    $PROP[4] = trim($propElementArr['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']);
                    $PROP[6] = $propElementArr['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'];
                    $PROP[22] = $propElementArr['PROPERTIES']['READY_DOCUMENTS']['VALUE'];
                    // первоначальные параметры созданой заявки end

                    $PROP[7] = json_encode([$startLiveLinks, $endLiveLinks]); // Начало и конец активности ссылки
                    $PROP[15] = $jsonE; // файлы с готовым переводом
                    $PROP[8] = "/transfer-view/?ELEMENT_ID=" . $idRequest; // ссылка с переводом для заказчика
                    $PROP[9] = $_POST['price']; // цена перевода заполненая переводчиком
                    $PROP[5] = 8; // Статус заявки id 8 - заявка "Выполненная"
                    $PROP[16] = trim($_POST['textTranslate']); // Текст с выполненым переводом
                    $PROP[17] = $userId; // user id переводчика

                    // Данные для инфоблока "Заявки" id - 1
                    $arLoadProductArray = [
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => 1,
                        "PROPERTY_VALUES" => $PROP,
                        "ACTIVE" => "Y",
                    ];

                    $discussion = $el->Update($idRequest, $arLoadProductArray);

                    // Выводим id обсуждения
                    // echo "id: " . $discussion . "<br>";


                // Если нету файлов для перевода
                } else {

                    $arrPropIdsFilesRequest[] = $fid;
                    $jsonE = json_encode($arrPropIdsFilesRequest);

                    $PROP = [];

                    // первоначальные параметры созданой заявки
                    $PROP[1] = $propElementArr['PROPERTIES']['CUSTOMER_ID']['VALUE'];
                    $PROP[2] = $propElementArr['PROPERTIES']['CUSTOMER_FIO']['VALUE'];
                    $PROP[3] = $propElementArr['PROPERTIES']['CUSTOMER_PHONE']['VALUE'];
                    $PROP[4] = trim($propElementArr['PROPERTIES']['CUSTOMER_COMMENT']['VALUE']);
                    $PROP[6] = $propElementArr['PROPERTIES']['ATTACHED_FILES_FOR_TRANSLATE']['VALUE'];
                    $PROP[22] = $propElementArr['PROPERTIES']['READY_DOCUMENTS']['VALUE'];
                    // первоначальные параметры созданой заявки end

                    $PROP[7] = json_encode([$startLiveLinks, $endLiveLinks]); // Начало и конец активности ссылки
                    $PROP[15] = $jsonE; // файлы с готовым переводом
                    $PROP[8] = "/transfer-view/?ELEMENT_ID=" . $idRequest; // ссылка с переводом для заказчика
                    $PROP[9] = $_POST['price']; // цена перевода заполненая переводчиком
                    $PROP[5] = 8; // Статус заявки id 8 - заявка "Выполненная"
                    $PROP[16] = trim($_POST['textTranslate']); // Текст с выполненым переводом
                    $PROP[17] = $userId; // user id переводчика

                    // Данные для инфоблока "Заявки" id - 1
                    $arLoadProductArray = [
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => 1,
                        "PROPERTY_VALUES" => $PROP,
                        "ACTIVE" => "Y",
                    ];

                    $discussion = $el->Update($idRequest, $arLoadProductArray);

                    // Выводим id обсуждения
                    // echo "id: " . $discussion . "<br>";
                }

                $i++;
            }

        }
    }
}
