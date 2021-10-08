<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <?php Asset::getInstance()->addString('<meta name="viewport" content="width=device-width,initial-scale=1">'); ?>
    <title><?php $APPLICATION->ShowTitle(); ?></title>

    <?php
    $APPLICATION->ShowHead();

    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/vendor/2.bundle.min.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/vendor/0.bundle.min.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/plugins/datepicker.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/plugins/alertify.min.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/custom.css');

    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/jquery.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/pdf.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/pdf.worker.js');

    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/alertify.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/jquery.validate.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/datepicker.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/datepicker.ru-RU.js');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/custom.js');

    $arUserGroups = CUser::GetUserGroup($USER->GetID());
    $findGroupAdmin = in_array("1", $arUserGroups);
    $findGroupMFC = in_array("5", $arUserGroups);
    $findGroupKeys = in_array("6", $arUserGroups);

    ?>

    <title>Сервис для переводческого агентства Ключи</title>

</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>


<?php if($findGroupAdmin || $findGroupMFC || $findGroupKeys): ?>


<?php
    // Заглушка
    if(!$findGroupAdmin): ?>
        <div class="banner-alert">На сайте ведутся технические работы!</div>
    <?php endif; ?>



<div id="keys-translr">
    <section class="keys-translr__box">

        <div class="keys-translr__box_sidebar">
            <div class="tac">
                <div>
                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo.svg" alt="logo"/>
                </div>
                <div class="mb-60">
                    <h2 class="color__white fs-24 tac mt-10 mb-10">Ключи</h2>
                    <h5 class="color__white fs-17">Добро пожаловать</h5>
                </div>
            </div>
            <div class="keys-translr__box-nav">
                <div class="keys-translr__box-nav-box">
                    <span class="color__white fs-16 keys-translr__box-nav-box-login">
                        <?= $USER->GetLogin(); ?>
                    </span>
                </div>

                <!--                <div class="keys-translr__box-nav-box">-->
                <!--                    <a href="#!" class="color__white fs-16 keys-translr__box-nav-box-settings">Настройки</a>-->
                <!--                </div>-->

                <div class="keys-translr__box-nav-box">
                    <a href="/notaries" class="color__white fs-16">Нотариусы</a>
                </div>
                <div class="keys-translr__box-nav-box">
                    <a href="/price-list" class="color__white fs-16">Прайс-лист</a>
                </div>
                <div class="keys-translr__box-nav-box">
                    <a href="/memo" class="color__white fs-16">Памятка</a>
                </div>
                <div class="keys-translr__box-nav-box">
                    <a href="/help" class="color__white fs-16 keys-translr__box-nav-box-prompt">Помощь</a>
                </div>

                <div class="keys-translr__box-nav-box">
                    <a href="#!" id="logout-btn" class="color__white fs-16 keys-translr__box-nav-box-logout">Выход</a>
                </div>
            </div>
        </div>

<?php endif; ?>