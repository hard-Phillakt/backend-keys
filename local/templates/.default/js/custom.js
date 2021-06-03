$(document).ready(function () {

    // $(".datepicker").datepicker({
    //     altFormat: "yy-mm-dd"
    // });


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


    var countScroll = 0;

    $(window).on('scroll', function (e) {
        if (countScroll >= 200) {
            $('#button-print').addClass('button__print')
        } else {
            $('#button-print').removeClass('button__print')
        }
        countScroll = window.scrollY;
    });

    // блок для создания ссылки с переводом
    $('input[name="createLink"]').on('change', function (e) {

        if (this.checked) {
            $('.form-group__translate-create-link').css({
                display: 'block'
            })

            $('.form-group__translate-create-link input[name="status"]').val('13')
        } else {
            $('.form-group__translate-create-link').css({
                display: 'none'
            })
            $('.form-group__translate-create-link input[name="status"]').val('')
        }
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
            title: "Заполните поле заголовок",
            fio: "Заполните поле ФИО",
            phone: "Заполните поле телефон",
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


