<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Страница помощи");


?>

<?php if($findGroupAdmin || $findGroupMFC || $findGroupKeys): ?>

<div class="keys-translr__box_wrap-request">
    <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
        <div id="humburger-btn" class="humburger-wrap">
            <span class="humburger-line humburger-start"></span>
            <span class="humburger-line humburger-middle"></span>
            <span class="humburger-line humburger-end"></span>
        </div>
        <div class="fjc-s fai-c">
            <div><img src="/local/templates/.default/img/logo/logo-blue.svg" alt="logo-blue"></div>
            <div class="pl-10 color__blue-light fs-20 fontw-700">
                Переводы
            </div>
        </div>
    </div>
    <div class="fjc-c fai-c mt-60">
        <h1>Страница помощи</h1>
    </div>
</div>

<?php else:
    header('Location: /'); ?>
<?php endif; ?>



<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
