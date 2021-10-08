<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

// Параметры для поиска и фильтров
$searchId = $_GET['search-id'];
$filterOpen = $_GET['filter-open'];
$filterClose = $_GET['filter-close'];
// Параметры для поиска и фильтров end


$userId = $USER->GetID();

$arUserGroups = CUser::GetUserGroup($userId);

$findGroupAdmin = in_array("1", $arUserGroups);
$findGroupMFC = in_array("5", $arUserGroups);
$findGroupKeys = in_array("6", $arUserGroups);

?>

<?php if ($findGroupAdmin || $findGroupMFC || $findGroupKeys): ?>

    <div class="keys-translr__box_wrap-list">
        <div>
            <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
                <div id="humburger-btn" class="humburger-wrap">
                    <span class="humburger-line humburger-start"></span>
                    <span class="humburger-line humburger-middle"></span>
                    <span class="humburger-line humburger-end"></span>
                </div>
                <div class="fjc-s fai-c">
                    <div>
                        <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo-blue.svg" alt="logo-blue"/>
                    </div>
                    <div class="pl-10 color__blue-light fs-20 fontw-700">
                        Переводы
                    </div>
                </div>
            </div>
            <div class="keys-translr__box-controls pl-60 pr-60 fjc-sb">

                <div class="mb-30">
                    <?php if ($findGroupAdmin || $findGroupMFC): ?>
                        <a href="/create-request/" class="keys-translr__box-controls_create-req">Создать заявку</a>
                    <?php endif; ?>
                </div>

                <div class="fai-c mb-30 fw-wrap">
                    <div class="fjc-sb fw-wrap">
                        <div class="keys-translr__box-controls_search mb-30 mr-60">
                            <form>
                                <input type="text" class="keys-translr__box-controls_search-text" name="search-id" placeholder="Поиск по № заявки"/>
                                <button type="submit" class="keys-translr__box-controls_search-btn">
                                    &#10148;
                                </button>
                            </form>
                        </div>
                        <div class="keys-translr__box-controls_filter fai-c mb-30">
                            <div class="fai-c fjc-sb">
                                <div>
                                    <a href="/list/" class="keys-translr__box-controls_filter-all">Все</a>
                                </div>
                                <div class="pl-20">
                                    <a href="/list/?filter-open=10" class="keys-translr__box-controls_filter-open">Активные</a>
                                </div>
                                <div class="pl-20">
                                    <a href="/list/?filter-close=10" class="keys-translr__box-controls_filter-close">Завершенные</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pl-60 pr-60">
                <div class="keys-translr__box-req">
                    <table class="keys-translr__box-req-table">
                        <tbody>

                        <tr>
                            <th>№</th>
                            <th>Наименование</th>
                            <th>Статус</th>
                            <th>Время публикации</th>
                        </tr>

                        <?php if (isset($searchId) && !empty($searchId)): ?>

                            <?
                            // По поиску
                            foreach ($arResult["ITEMS"] as $arItemSearch): ?>

                                <?
                                $this->AddEditAction($arItemSearch['ID'], $arItemSearch['EDIT_LINK'], CIBlock::GetArrayByID($arItemSearch["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItemSearch['ID'], $arItemSearch['DELETE_LINK'], CIBlock::GetArrayByID($arItemSearch["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>
                                <?php if ($searchId === $arItemSearch['ID']): ?>

                                    <?php

                                    $strToArrGetUsers = json_decode($arItemSearch['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                    $customerId = json_decode($arItemSearch['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                    // Меняем местами ключи с их значениями в массиве
                                    $flipArray = array_flip($customerId);

                                    // array_key_exists присутствует ли в массиве указанный ключ или индекс
                                    if (array_key_exists((int) $userId, $flipArray) || $findGroupKeys || $findGroupAdmin): ?>

                                        <tr>
                                            <td><?= $arItemSearch['ID'] ?></td>
                                            <td>
                                                <a href="/list/?ELEMENT_ID=<?= $arItemSearch['ID'] ?>" class="keys-translr__box-req-link">
                                                    <?= $arItemSearch['NAME'] ?>
                                                </a>
                                            </td>
                                            <td>

                                                <?php

                                                switch ($arItemSearch['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                                    case (6): // В работе  6
                                                        echo '<span class="status status-primary"></span>';
                                                        break;
                                                    case (7): // Срочная  7
                                                        echo '<span class="status status-danger"></span>';
                                                        break;
                                                    case (8): // Выполненная  8
                                                        echo '<span class="status status-success"></span>';
                                                        break;
                                                    case (9): // Ошибка в переводе 9
                                                        echo '<span class="status status-warning"></span>';
                                                        break;
                                                    case (10): // Завершена 10
                                                        echo '<span class="status status-default"></span>';
                                                        break;

                                                }

                                                ?>

                                                <?= $arItemSearch['PROPERTIES']['STATUS']['VALUE'] ?>
                                            </td>
                                            <td><?= $arItemSearch['TIMESTAMP_X'] ?> </td>
                                        </tr>

                                    <?php endif; ?>

                                <?php endif; ?>

                            <? endforeach; ?>

                        <?php endif; ?>



                        <?php if (isset($filterOpen) && !empty($filterOpen)): ?>

                            <?
                            // По фильтру (открытые)
                            foreach ($arResult["ITEMS"] as $arItemOpen): ?>

                                <?
                                $this->AddEditAction($arItemOpen['ID'], $arItemOpen['EDIT_LINK'], CIBlock::GetArrayByID($arItemOpen["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItemOpen['ID'], $arItemOpen['DELETE_LINK'], CIBlock::GetArrayByID($arItemOpen["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>

                                <?php
                                // Выводим только закрытые
                                if ($arItemOpen['PROPERTIES']['STATUS']['VALUE_ENUM_ID'] != 10): ?>

                                    <?php

                                    $strToArrGetUsers = json_decode($arItemOpen['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                    $customerId = json_decode($arItemOpen['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                    // Меняем местами ключи с их значениями в массиве
                                    $flipArray = array_flip($customerId);

                                    // array_key_exists присутствует ли в массиве указанный ключ или индекс
                                    if (array_key_exists((int) $userId, $flipArray) || $findGroupKeys || $findGroupAdmin): ?>

                                        <tr>
                                            <td><?= $arItemOpen['ID'] ?></td>
                                            <td>
                                                <a href="/list/?ELEMENT_ID=<?= $arItemOpen['ID'] ?>" class="keys-translr__box-req-link">
                                                    <?= $arItemOpen['NAME'] ?>
                                                </a>
                                            </td>
                                            <td>

                                                <?php

                                                switch ($arItemOpen['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                                    case (6): // В работе  6
                                                        echo '<span class="status status-primary"></span>';
                                                        break;
                                                    case (7): // Срочная  7
                                                        echo '<span class="status status-danger"></span>';
                                                        break;
                                                    case (8): // Выполненная  8
                                                        echo '<span class="status status-success"></span>';
                                                        break;
                                                    case (9): // Ошибка в переводе 9
                                                        echo '<span class="status status-warning"></span>';
                                                        break;
                                                    case (10): // Завершена 10
                                                        echo '<span class="status status-default"></span>';
                                                        break;

                                                }

                                                ?>

                                                <?= $arItemOpen['PROPERTIES']['STATUS']['VALUE'] ?>
                                            </td>
                                            <td><?= $arItemOpen['TIMESTAMP_X'] ?> </td>
                                        </tr>

                                    <?php endif; ?>

                                <?php endif; ?>

                            <? endforeach; ?>

                        <?php endif; ?>



                        <?php if (isset($filterClose) && !empty($filterClose)): ?>

                            <?
                            // По фильтру (закрытые)
                            foreach ($arResult["ITEMS"] as $arItemClose): ?>
                                <?
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>

                                <?php
                                // Выводим только закрытые
                                if ($arItemClose['PROPERTIES']['STATUS']['VALUE_ENUM_ID'] == 10): ?>

                                    <?php if ($arItemClose['PROPERTIES']['CUSTOMER_ID']['VALUE'] == $userId || $findGroupKeys || $findGroupAdmin): ?>

                                        <tr>
                                            <td><?= $arItemClose['ID'] ?></td>
                                            <td>
                                                <a href="/list/?ELEMENT_ID=<?= $arItemClose['ID'] ?>" class="keys-translr__box-req-link">
                                                    <?= $arItemClose['NAME'] ?>
                                                </a>
                                            </td>
                                            <td>

                                                <?php

                                                switch ($arItemClose['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                                    case (6): // В работе  6
                                                        echo '<span class="status status-primary"></span>';
                                                        break;
                                                    case (7): // Срочная  7
                                                        echo '<span class="status status-danger"></span>';
                                                        break;
                                                    case (8): // Выполненная  8
                                                        echo '<span class="status status-success"></span>';
                                                        break;
                                                    case (9): // Ошибка в переводе 9
                                                        echo '<span class="status status-warning"></span>';
                                                        break;
                                                    case (10): // Завершена 10
                                                        echo '<span class="status status-default"></span>';
                                                        break;

                                                }

                                                ?>

                                                <?= $arItemClose['PROPERTIES']['STATUS']['VALUE'] ?>
                                            </td>
                                            <td><?= $arItemClose['TIMESTAMP_X'] ?> </td>
                                        </tr>

                                    <?php endif; ?>

                                <?php endif; ?>

                            <? endforeach; ?>

                        <?php endif; ?>


                        <?php

                        if (empty($searchId) && empty($filterClose) && empty($filterOpen)): ?>
                            <?

                            // Без поиска и фильтров
                            foreach ($arResult["ITEMS"] as $arItem): ?>

                                <?php

                                $strToArrGetUsers = json_decode($arItem['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                $customerId = json_decode($arItem['PROPERTIES']['CUSTOMER_ID']['VALUE'], true);

                                // Меняем местами ключи с их значениями в массиве
                                $flipArray = array_flip($customerId);

                                // array_key_exists присутствует ли в массиве указанный ключ или индекс
                                if (array_key_exists((int) $userId, $flipArray) || $findGroupKeys || $findGroupAdmin): ?>

                                    <?
                                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                    ?>

                                    <tr>

                                        <td><?= $arItem['ID'] ?></td>
                                        <td>
                                            <a href="/list/?ELEMENT_ID=<?= $arItem['ID'] ?>" class="keys-translr__box-req-link">
                                                <?= $arItem['NAME'] ?>
                                            </a>
                                        </td>
                                        <td>

                                            <?php

                                            switch ($arItem['PROPERTIES']['STATUS']['VALUE_ENUM_ID']) {

                                                case (6): // В работе  6
                                                    echo '<span class="status status-primary"></span>';
                                                    break;
                                                case (7): // Срочная  7
                                                    echo '<span class="status status-danger"></span>';
                                                    break;
                                                case (8): // Выполненная  8
                                                    echo '<span class="status status-success"></span>';
                                                    break;
                                                case (9): // Ошибка в переводе 9
                                                    echo '<span class="status status-warning"></span>';
                                                    break;
                                                case (10): // Завершена 10
                                                    echo '<span class="status status-default"></span>';
                                                    break;

                                            }

                                            ?>

                                            <?= $arItem['PROPERTIES']['STATUS']['VALUE'] ?>
                                        </td>
                                        <td><?= $arItem['TIMESTAMP_X'] ?> </td>
                                    </tr>

                                <?php endif; ?>

                            <? endforeach; ?>

                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>


                <div class="mt-40 mb-40">
                    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>

                        <?= $arResult["NAV_STRING"]; ?>

                    <? endif; ?>
                </div>

            </div>
        </div>
    </div>

<?php else:
    header('Location: /'); ?>

<?php endif; ?>