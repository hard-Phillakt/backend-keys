<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Создание заявки");

?>

    <?php if($findGroupMFC || $findGroupAdmin):?>

    <div class="keys-translr__box_wrap-request">
        <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
            <div id="humburger-btn" class="humburger-wrap">
                <span class="humburger-line humburger-start"></span>
                <span class="humburger-line humburger-middle"></span>
                <span class="humburger-line humburger-end"></span>
            </div>
            <div class="fjc-s fai-c">
                <div><img src="<?= DEFAULT_TEMPLATE_PATH  ?>/img/logo/logo-blue.svg" alt="logo-blue" /></div>
                <div class="pl-10 color__blue-light fs-20 fontw-700">
                    Переводы
                </div>
            </div>
        </div>
        <div class="pl-60 pr-60">
            <div class="fjc-s fai-c fw-wrap mb-30">
                <div class="fjc-s mr-40">
                    <a href="/list/" class=" fjc-s keys-translr__box-discussion-header_back-link mb-20">
                        <span class="pr-10">
                            <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/icons/left-arrow.svg" alt="left-arrow"/>
                        </span>
                        Все заявки
                    </a>
                </div>
                <h1 class="color__black fs-28 mb-20">Новая заявка</h1>
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
                        <label for="keys-translr__box-form_ready-documents">Место выдачи готовых документов</label>
                    </div>
                    <div class="custom-select">
                        <select name="ready_documents" id="ready-documents">
                            <option value="По месту подачи заявки">По месту подачи заявки</option>
                            <option value="По месту подачи заявки">По месту подачи заявки</option>
                            <option value="Офис ООО «Ключи»">Офис ООО «Ключи»</option>
                        </select>
                    </div>
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

    <script>
        $(document).ready(function () {
            // Отправка ajax создание заявки
            $('#create-req').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2
                    },
                    fio: {
                        required: true,
                        minlength: 5
                    },
                    phone: {
                        required: true,
                        minlength: 11
                    },
                },
                messages: {
                    title: "Заполните поле «Заголовок»",
                    fio: "Заполните поле «ФИО заказчика»",
                    phone: "Заполните поле «Телефон заказчика»",
                },
                submitHandler: function () {
                    var form = $('#create-req');
                    var formData = new FormData($(form)[0]);

                    $.ajax({
                        url: '/ajax/request-el-add/',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        type: 'post',
                        success: function (res) {
                            var response = JSON.parse(res);

                            if(response.countFiles){
                                var alertifyText = 'Создана заявка № ' + response.reqId + ' загруженно ' + response.countFiles +  ' файл(ов)';
                                alertify.notify(alertifyText, 'success', 1000, function(){  console.log('dismissed'); }); // success
                                $('#create-req .keys-translr__box-form_files').html('<li>Выбрать файл(ы)*</li>'); // сброс li
                                $(form)[0].reset(); // сброс полей формы
                            }else {
                                alertify.error('Загрузите файлы для перевода!'); // error
                            }
                        }
                    });

                }
            });
            // Отправка ajax создание заявки end
        });

        var x, i, j, l, ll, selElmnt, a, b, c;
        /*look for any elements with the class "custom-select":*/
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            ll = selElmnt.length;
            /*for each element, create a new DIV that will act as the selected item:*/
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            /*for each element, create a new DIV that will contain the option list:*/
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < ll; j++) {
                /*for each option in the original select element,
                create a new DIV that will act as an option item:*/
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    /*when an item is clicked, update the original select box,
                    and the selected item:*/
                    var y, i, k, s, h, sl, yl;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    sl = s.length;
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < sl; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            yl = y.length;
                            for (k = 0; k < yl; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                /*when the select box is clicked, close any other select boxes,
                and open/close the current select box:*/
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }
        function closeAllSelect(elmnt) {
            /*a function that will close all select boxes in the document,
            except the current select box:*/
            var x, y, i, xl, yl, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            xl = x.length;
            yl = y.length;
            for (i = 0; i < yl; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < xl; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }
        /*if the user clicks anywhere outside the select box,
        then close all select boxes:*/
        document.addEventListener("click", closeAllSelect);
    </script>

    <?php else:
            header('Location: /');?>

    <?php endif;?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>