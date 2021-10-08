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

});


// Кастомный Select
function customSelect(){
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
            c.addEventListener("click", function (e) {
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
        a.addEventListener("click", function (e) {
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
}

