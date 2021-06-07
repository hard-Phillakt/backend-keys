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

    // Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">');
    // Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">');
    // Asset::getInstance()->addString('<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/vendor/2.bundle.min.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/vendor/0.bundle.min.css');
    Asset::getInstance()->addString('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" integrity="sha512-IXuoq1aFd2wXs4NqGskwX2Vb+I8UJ+tGJEu/Dc0zwLNKeQ7CW3Sr6v0yU3z5OQWe3eScVIkER4J9L7byrgR/fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/custom.css');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/plugins/datepicker.css');


    Asset::getInstance()->addString('<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>');
    // Asset::getInstance()->addString('<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>');
    // Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/plugins/pignose/pignose.calendar.min.js');
    Asset::getInstance()->addJs('//mozilla.github.io/pdf.js/build/pdf.js');

    Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>');
    Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>');
    // Asset::getInstance()->addJs('https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js');

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
                    <a href="#!" id="logout-btn" class="color__white fs-16 keys-translr__box-nav-box-logout">Выход</a>
                </div>
            </div>
        </div>

<?php endif; ?>