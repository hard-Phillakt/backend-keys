<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создание заявки");

?>


<section class="app-wrapper">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <h1>Создание заявки</h1>
            </div>

            <div class="col-lg-12">
                <form action="/ajax/request-el-add/index.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Заголовок</label>
                        <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Введите заголовок" value="First title">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">ФИО заказчика</label>
                        <input type="text" name="fio" class="form-control" id="exampleInputPassword1" placeholder="Введите ФИО заказчика" value="FIO customer">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPhone">Телефон заказчика</label>
                        <input type="text" name="phone" class="form-control" id="exampleInputPhone" placeholder="Введите телефон заказчика" value="Phone customer">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Файл для перевода</label>
                        <input type="file" id="exampleInputFile" name="upfiles[]" accept=".doc, .docx, .pdf, .jpg, .png" multiple>
                        <p class="help-block">Выбрать файл.</p>
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea">Комментарий</label>
                        <textarea class="form-control" name="comment" rows="5" id="exampleTextarea">Comment customer</textarea>
                    </div>

                    <input type="hidden" name="action" value="addElementRequest">

                    <button type="submit" class="btn btn-primary">Отправить заявку</button>

                </form>
            </div>
        </div>
    </div>
</section>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>