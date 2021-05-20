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

    Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">');
    Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">');
    Asset::getInstance()->addString('<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">');
    Asset::getInstance()->addCss(DEFAULT_TEMPLATE_PATH . '/css/custom.css');


    Asset::getInstance()->addString('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>');
    Asset::getInstance()->addString('<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>');
    Asset::getInstance()->addString('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>');
    Asset::getInstance()->addJs(DEFAULT_TEMPLATE_PATH . '/js/custom.js');
    ?>
    <title>Document</title>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>