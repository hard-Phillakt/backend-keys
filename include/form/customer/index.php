<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 03.06.2021
 * Time: 19:14
 */

$rsUser = $USER::GetByID($USER->GetID());
$arUser = $rsUser->Fetch(); // данные о пользователе
$arGroups = $USER->GetUserGroupArray(); // id групп пользователя
?>

<form id="create-discussion-customer" class="keys-translr__box-form pt-30 pb-30">
    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_title">Заголовок</label>
        </div>
        <input type="text" id="keys-translr__box-form_title" name="title" value="" required />
    </div>
    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_files">Файлы для перевода</label>
        </div>
        <div class="fjc-s fai-c">
            <ul class="keys-translr__box-form_files">
                <li>Выбрать файл(ы)*</li>
            </ul>
            <label for="keys-translr__box-form_files" class="keys-translr__box-form_fake-files-btn">Выбрать</label>
            <input type="file" id="keys-translr__box-form_files" name="upfiles[]" multiple="multiple" accept=".doc, .docx, .pdf, .jpg, .png" />
        </div>
    </div>
    <div class="mb-20">
        <div><label>Выбрать статус</label></div>
        <div class="fjc-s fw-wrap">

            <div class="mb-10 mr-20">
                <div>
                    <input class="status-checkbox" type="radio" id="keys-translr__box-form_status-1" name="status" value="11" checked="checked"/>
                    <label for="keys-translr__box-form_status-1">В работе</label>
                </div>
            </div>

            <div class="mb-10 mr-20">
                <div>
                    <input class="status-checkbox" type="radio" id="keys-translr__box-form_status-2" name="status" value="12"/>
                    <label for="keys-translr__box-form_status-2">Срочная</label>
                </div>
            </div>

            <div class="mb-10 mr-20">
                <div>
                    <input
                        class="status-checkbox"
                        type="radio"
                        id="keys-translr__box-form_status-3"
                        name="status"
                        value="14"
                    />
                    <label for="keys-translr__box-form_status-3">Ошибка в переводе</label>
                </div>
            </div>
            <div class="mb-10 mr-20">
                <div>
                    <input
                        class="status-checkbox"
                        type="radio"
                        id="keys-translr__box-form_status-4"
                        name="status"
                        value="15"
                    />
                    <label for="keys-translr__box-form_status-4">Завершена</label>
                </div>
            </div>

        </div>
    </div>
    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_comment">Комментарий</label>
        </div>
        <textarea name="comment" id="keys-translr__box-form_comment" cols="30" rows="5"></textarea>
    </div>

    <input type="hidden" name="idGroupUser" value='<?= json_encode($arGroups); ?>'>
    <input type="hidden" name="idRequest" value="<?= $_GET['ELEMENT_ID'] ?>">
    <input type="hidden" name="action" value="addElementDiscussionCustomer">
    <input type="hidden" name="userId" value="<?= $USER->GetID();?>">

    <div>
        <button type="submit" class="button__primary color__white pl-60 pr-60 pt-10 pb-10 fs-16">
            Отправить заявку
        </button>
    </div>
</form>

<script>

    $(document).ready(function () {

        function scrollBoxToBottom (){
            // Скролл в конец обсуждения
            var scrollBox =  document.querySelector('.keys-translr__box-discussion-view');
            if(scrollBox){
                var topHeight = 0;
                $('.keys-translr__box-discussion-bshadow').each(function (i, item) {
                    topHeight += $(item).height();
                });
                scrollBox.scroll({ top: topHeight, left: 0 });
            }
            // Скролл в конец обсуждения
        }

        // Сообщение от лица заказчика
        $('#create-discussion-customer').validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                title: "Заполните поле «Заголовок»",
            },
            submitHandler: function () {
                var form = $('#create-discussion-customer');
                var formData = new FormData($(form)[0]);

                $.ajax({
                    url: '/ajax/discussion-customer-add/',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    type: 'post',
                    success: function (resJson) {

                        var res = JSON.parse(resJson);

                        if(res.reqId && res.countFiles == 0){
                            alertify.message('Создано сообщение № ' + res.reqId, 1000);

                            var data = {"id": <?= $_GET['ELEMENT_ID'] ?>};

                            $.ajax({
                                url: '/ajax/discussion-ajax-list/',
                                data: {"data": JSON.stringify(data)},
                                type: 'post',
                                success: function (resHtml) {
                                    if(resHtml){
                                        $('.keys-translr__box-discussion-view').html(resHtml);
                                        scrollBoxToBottom();
                                    }
                                }
                            });

                        }else if(res.reqId && res.countFiles && res.createLink){
                            alertify.success('Создано сообщение № ' + res.reqId + ' и сгенерирована ссылка с переводом', 1000);


                        }else if(res.reqId == null && res.countFiles == 0 && res.createLink == 0){
                            alertify.error('Не возможно создать ссылку без файлов .PDF', 1000);
                        }


                        $('.keys-translr__box-form_files').html('<li>Выбрать файл(ы)*</li>'); // сброс li
                    }
                });

                $(form)[0].reset(); // сброс полей формы

            }
        });
        // Сообщение от лица заказчика end

    });
</script>
