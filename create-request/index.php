<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Создание заявки");

?>

    <?php if($findGroupMFC || $findGroupAdmin):?>

    <div class="keys-translr__box_wrap-request">
        <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
            <div class="fjc-s fai-c">
                <div><img src="<?= DEFAULT_TEMPLATE_PATH  ?>/img/logo/logo-blue.svg" alt="logo-blue" /></div>
                <div class="pl-10 color__blue-light fs-20 fontw-700">
                    Переводы
                </div>
            </div>
        </div>
        <div class="pl-60 pr-60">
            <div class="mb-30">
                <h1 class="color__black fs-28">Новая заявка</h1>
            </div>
            <form id="create-req" class="keys-translr__box-form pt-30 pb-30 pl-30 pr-30">
                <div class="mb-20">
                    <div>
                        <label for="keys-translr__box-form_title">Заголовок</label>
                    </div>
                    <input type="text" id="keys-translr__box-form_title" name="title" value="" required />
                </div>
                <div class="mb-20">
                    <div>
                        <label for="keys-translr__box-form_fio">ФИО заказчика</label>
                    </div>
                    <input type="text" id="keys-translr__box-form_fio" name="fio" value="" required />
                </div>
                <div class="mb-20">
                    <div>
                        <label for="keys-translr__box-form_phone">Телефон заказчика</label>
                    </div>
                    <input type="text" id="keys-translr__box-form_phone" name="phone" value="" required />
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
                                <input class="status-checkbox" type="radio" id="keys-translr__box-form_status-1" name="status" value="6" checked="checked"/>
                                <label for="keys-translr__box-form_status-1">В работе</label>
                            </div>
                        </div>

                        <div class="mb-10 mr-20">
                            <div>
                                <input class="status-checkbox" type="radio" id="keys-translr__box-form_status-2" name="status" value="7"/>
                                <label for="keys-translr__box-form_status-2">Срочная</label>
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

                <input type="hidden" name="action" value="addElementRequest">
                <input type="hidden" name="userId" value="<?= $USER->GetID();?>">

                <div>
                    <button type="submit" class="button__primary color__white pl-60 pr-60 pt-10 pb-10 fs-16">
                        Отправить заявку
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php else: ?>

        <div class="mt-60 pl-60 pr-60">
            <div class="fjc-c mb-30">
                <h1 class="color__black fs-28"><span class="status-str__danger">Доступ ограничен!</span></h1>
            </div>
        </div>

    <?php endif;?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>