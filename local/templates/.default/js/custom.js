$(function () {
    $(".datepicker").datepicker({
        altFormat: "yy-mm-dd"
    });



    // блок для создания ссылки с переводом
    $('input[name="createLink"]').on('change', function (e) {

        console.log(this.checked);

        if(this.checked){
            $('.form-group__translate-create-link').css({
                display: 'block'
            })

            $('.form-group__translate-create-link input[name="status"]').val('13')
        }else {
            $('.form-group__translate-create-link').css({
                display: 'none'
            })
            $('.form-group__translate-create-link input[name="status"]').val('')
        }
    });

});