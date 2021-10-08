<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 03.06.2021
 * Time: 19:15
 */


$rsUser = $USER::GetByID($USER->GetID());
$arUser = $rsUser->Fetch(); // данные о пользователе
$arGroups = $USER->GetUserGroupArray(); // id групп пользователя

?>

<form id="create-discussion-translator" class="keys-translr__box-form pt-30 pb-30">
    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_title">Заголовок</label>
        </div>
        <input type="text" id="keys-translr__box-form_title" name="title" value="" required/>
    </div>
    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_files">Файл для перевода (не больше 1-го .pdf)</label>
        </div>
        <div class="fjc-s fai-c">
            <ul class="keys-translr__box-form_files">
                <li>Выбрать файл*</li>
            </ul>
            <label for="keys-translr__box-form_files" class="keys-translr__box-form_fake-files-btn">Выбрать</label>
            <input type="file" id="keys-translr__box-form_files" name="upfiles[]" multiple="multiple" accept=".doc, .docx, .pdf, .jpg, .png"/>
        </div>
    </div>
    <div class="mb-20">
        <div class="fjc-s fw-wrap">
            <div class="mr-20">
                <div>
                    <input class="status-checkbox checkbox-view" type="checkbox" name="createLink" id="keys-translr__box-form_status-6">
                    <label for="keys-translr__box-form_status-6">Сформировать ссылку</label>
                </div>
            </div>
        </div>
    </div>


    <div class="mb-20">
        <div class="form-group__translate-create-link">
            <div class="fjc-sb fw-wrap">
                <div class="mb-20 mr-60 flex__33">
                    <div>
                        <label for="keys-translr__box-form_title">Начало активности ссылки </label>
                    </div>
                    <input type="text" id="keys-translr__box-form_calendar-start" data-toggle="datepicker" name="startLiveLinks"/>
                </div>
                <div class="mb-20 flex__33">
                    <div>
                        <label for="keys-translr__box-form_title">Конец активности ссылки</label>
                    </div>
                    <input type="text" id="keys-translr__box-form_calendar-end" data-toggle="datepicker" name="endLiveLinks"/>
                </div>
            </div>

            <div class="mb-20 flex__33">
                <div>
                    <label for="keys-translr__box-form_price">Цена за перевод</label>
                </div>
                <input type="text" id="keys-translr__box-form_price" name="price"/>
            </div>
        </div>
    </div>


    <div class="mb-20">
        <div>
            <label for="keys-translr__box-form_comment">Комментарий</label>
        </div>
        <textarea name="comment" id="keys-translr__box-form_comment" cols="30" rows="5"></textarea>
    </div>

    <input type="hidden" name="status" value="11"/>
    <input type="hidden" name="idGroupUser" value='<?= json_encode($arGroups); ?>'>
    <input type="hidden" name="idRequest" value="<?= $_GET['ELEMENT_ID'] ?>">
    <input type="hidden" name="action" value="addElementDiscussionTranslator">
    <input type="hidden" name="userId" value="<?= $USER->GetID(); ?>">

    <div>
        <button type="submit" class="button__primary color__white pl-60 pr-60 pt-10 pb-10 fs-16">
            Отправить заявку
        </button>
    </div>
</form>

<script>
    $(document).ready(function () {

        function scrollBoxToBottom() {
            // Скролл в конец обсуждения
            var scrollBox = document.querySelector('.keys-translr__box-discussion-view');
            if (scrollBox) {
                var topHeight = 0;
                $('.keys-translr__box-discussion-bshadow').each(function (i, item) {
                    topHeight += $(item).height();
                });
                scrollBox.scroll({top: topHeight, left: 0});
            }
            // Скролл в конец обсуждения
        }


        // Сообщение от лица переводчика
        $('#create-discussion-translator').validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                startLiveLinks: {
                    required: true,
                    minlength: 8
                },
                endLiveLinks: {
                    required: true,
                    minlength: 8
                },
                price: {
                    required: true,
                    minlength: 1
                }
            },
            messages: {
                title: "Заполните поле  «Заголовок»",
                price: "Заполните поле  «Цена за перевод»",
                startLiveLinks: "Заполните поле  «Начало активности ссылки»",
                endLiveLinks: "Заполните поле  «Конец активности ссылки»"
            },
            submitHandler: function () {
                var form = $('#create-discussion-translator');
                var formData = new FormData($(form)[0]);

                $.ajax({
                    url: '/ajax/discussion-translator-add/',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    type: 'post',
                    success: function (resJson) {

                        var res = JSON.parse(resJson);

                        if (res.status_code == 1) {
                            alertify.message('Создано сообщение', 1000);

                            var data = {"ID": <?= $_GET['ELEMENT_ID'] ?>};
                            $.ajax({
                                url: '/ajax/discussion-ajax-list/',
                                data: {"data": JSON.stringify(data)},
                                type: 'post',
                                success: function (resHtml) {
                                    if (resHtml) {
                                        $('.keys-translr__box-discussion-view').html(resHtml);
                                        scrollBoxToBottom();
                                    }
                                }
                            });
                        }

                        if (res.status_code == 2) {
                            alertify.success('Создано сообщение и сгенерирована ссылка с переводом', 1000);

                            var data = {"ID": <?= $_GET['ELEMENT_ID'] ?>};

                            // Обновляем список сообщений
                            // .keys-translr__box-discussion-view
                            $.ajax({
                                url: '/ajax/discussion-ajax-list/',
                                data: {"data": JSON.stringify(data)},
                                type: 'post',
                                success: function (resHtml) {
                                    if (resHtml) {
                                        $('.keys-translr__box-discussion-view').html(resHtml);
                                        scrollBoxToBottom();
                                    }
                                }
                            });

                            // Обновляем состояние заявки в закрепе
                            // #state-current-data-request
                            $.ajax({
                                url: '/ajax/state-current-data-request/',
                                data: {"data": JSON.stringify(data)},
                                type: 'post',
                                success: function (resHtml) {
                                    if (resHtml) {
                                        $('#state-current-data-request').html(resHtml);
                                    }
                                }
                            });

                        }

                        if (res.status_code == 3) {
                            alertify.error('Не больше одного файла .PDF', 1000);
                        }

                        if (res.status_code == 4) {
                            alertify.error('Не возможно создать ссылку без файлов .PDF', 1000);
                        }

                        // if (res.reqId && res.countFiles == null) {
                        //     alertify.message('Создано сообщение № ' + res.reqId, 1000);
                        // }
                        //
                        // if (res.reqId && res.countFiles && res.createLink) {
                        //     alertify.success('Создано сообщение №' + res.reqId + ' и сгенерирована ссылка с переводом', 1000);
                        //
                        // }
                        //
                        // if (res.reqId && res.countFiles == 2 && res.createLink) {
                        //     alertify.error('Не больше одного файла .PDF', 1000);
                        // }
                        //
                        // if (res.reqId && res.countFiles == 1 && res.createLink) {
                        //     alertify.error('Не возможно создать ссылку без файлов .PDF', 1000);
                        // }

                        $('.keys-translr__box-form_files').html('<li>Выбрать файл(ы)*</li>'); // сброс li
                    }
                });

                $(form)[0].reset(); // сброс полей формы
                $('.form-group__translate-create-link').css({
                    display: 'none'
                })
            }
        });
        // Сообщение от лица переводчика end
    });
</script>
