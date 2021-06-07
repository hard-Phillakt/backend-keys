$(document).ready(function () {

    // Авторизация пользователя
    $('#login-form').validate({
        rules: {
            login: {
                required: true,
            },
            password: {
                required: true,
            }
        },
        messages: {
            login: "Заполните поле «Логин»",
            password: "Заполните поле «Пароль»",
        },
        submitHandler: function () {


            var form = $('#login-form');
            console.log(form.serialize());

            $.ajax({
                url: '/ajax/auth/login/',
                data: form.serialize(),
                type: 'post',
                success: function (res) {
                    console.dir(res);
                    if(res){
                        window.location.assign('/list/');
                    }else {
                        alertify.error('Ошибка авторизации!'); // error
                    }
                }
            });

        }
    });
    // Авторизация пользователя end

    // Выход из аккаунта
    $('#logout-btn').on('click', function () {

        $.ajax({
            url: '/ajax/auth/logout/',
            data: 'logout=1',
            type: 'post',
            success: function (res) {
                console.log(res);

                if(res){
                    window.location.assign('/');
                }else {
                    alertify.error('Не удачный выход из аккаунта!'); // error
                }
            }
        });

    });
    // Выход из аккаунта

    var classFiles = $(".keys-translr__box-form_files");
    var obj;
    $("#keys-translr__box-form_files").on("change", function (e) {
        $(classFiles).eq(0).children().remove();
        obj = Array.from(e.target.files);
        for (var key in obj) {
            $(classFiles).append(`<li>${obj[key].name}</li>`);
        }
    });
    //  Для формы "Создание заявки" end

    // humburger
    $('#humburger-btn').on('click', function () {

        if (!$('.keys-translr__box_sidebar').hasClass('keys-translr__box_sidebar-view')) {
            $('.keys-translr__box_sidebar').addClass('keys-translr__box_sidebar-view');
        } else {
            $('.keys-translr__box_sidebar').removeClass('keys-translr__box_sidebar-view');
        }
    });
    // humburger end

    // Высота блока обсуждения
    if ($('#keys-translr .keys-translr__box-discussion-view').children().length > 2) {
        $('#keys-translr .keys-translr__box-discussion-view').css({
            height: 650
        })
    }
    // Высота блока обсуждения end

    // убираем отступ mb-40 у последнего элемента
    $('.keys-translr__box-discussion-view').children().last().removeClass('mb-40');
    // убираем отступ mb-40 у последнего элемента end


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

    var countScroll = 0;
    $(window).on('scroll', function (e) {
        if (countScroll >= 200) {
            $('#button-print').addClass('button__print')
        } else {
            $('#button-print').removeClass('button__print')
        }
        countScroll = window.scrollY;
    });

    var monthNames = ["01", "02", "03", "04", "05", "06",
        "07", "08", "09", "10", "11", "12"];
    var dateObj = new Date();
    var month = monthNames[dateObj.getMonth()];
    var day =  dateObj.getDate(); //String(dateObj.getDate()).padStart(2, '0');
    var year = dateObj.getFullYear();
    var outputStart =  day + '-' + month  + '-' + year;
    var outputEnd = day + 7 + '-' + month  + '-' + year;

    // блок для создания ссылки с переводом + автозаполнение полей
    $('input[name="createLink"]').on('change', function (e) {

        if (this.checked) {
            $('.form-group__translate-create-link').css({
                display: 'block'
            });

            $('#keys-translr__box-form_calendar-start').val(outputStart);
            $('#keys-translr__box-form_calendar-end').val(outputEnd);

            $('input[name="status"]').val('13');
        } else {
            $('.form-group__translate-create-link').css({
                display: 'none'
            });

            $('#keys-translr__box-form_calendar-start').val('');
            $('#keys-translr__box-form_calendar-end').val('');

            $('input[name="status"]').val('11');
        }

    });


    // Календарь для формирования время жизни ссылки // Doc https://fengyuanchen.github.io/datepicker/
    $('[data-toggle="datepicker"]').datepicker({
        format: 'dd-mm-yyyy',
        language: 'ru-RU',
        startDate: Date.now(),
        endDate: dateObj.setDate(dateObj.getDate() + 7),
    });

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

                    console.log(resJson);

                    var res = JSON.parse(resJson);

                    if(res.reqId && res.countFiles == 0){
                        alertify.message('Создано сообщение № ' + res.reqId, 1000);
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
                    console.log(resJson);

                    var res = JSON.parse(resJson);

                    if(res.reqId && res.countFiles == 0){
                        alertify.message('Создано сообщение № ' + res.reqId, 1000);
                    }else if(res.reqId && res.createLink){
                        alertify.success('Создано сообщение № ' + res.reqId + ' и сгенерирована ссылка с переводом', 1000);
                    }else if(res.reqId == null && res.countFiles == 0 && res.createLink == 0){
                        alertify.error('Не возможно создать ссылку без файлов .PDF', 1000);
                    }else if(res.reqId == null && res.oneFiles == null){
                        alertify.error('Не больше одного файла .PDF', 1000);
                    }

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


