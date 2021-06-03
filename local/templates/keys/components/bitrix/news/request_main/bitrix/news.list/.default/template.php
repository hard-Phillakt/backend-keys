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


$arUserGroups = CUser::GetUserGroup($USER->GetID());
$findGroupAdmin = in_array("1", $arUserGroups);
$findGroupMFC = in_array("6", $arUserGroups);
$findGroupKeys = in_array("5", $arUserGroups);

?>

<?php if($findGroupAdmin || $findGroupMFC || $findGroupKeys): ?>

    <div class="keys-translr__box_wrap-list">
        <div>
            <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
                <div id="humburger-btn" class="humburger-wrap">
                    <span class="humburger-line humburger-start"></span>
                    <span class="humburger-line humburger-middle"></span>
                    <span class="humburger-line humburger-end"></span>
                </div>
                <div class="fjc-s fai-c">
                    <div><img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo-blue.svg" alt="logo-blue"/></div>
                    <div class="pl-10 color__blue-light fs-20 fontw-700">
                        Переводы
                    </div>
                </div>
            </div>
            <div class="keys-translr__box-controls pl-60 pr-60 fjc-sb">
                <div class="mb-30">
                    <a href="/create-request/" class="keys-translr__box-controls_create-req">Создать заявку</a>
                </div>
                <div class="fai-c mb-30 fw-wrap">
                    <div class="fjc-sb fw-wrap">
                        <div class="keys-translr__box-controls_search mb-30 mr-60">
                            <form>
                                <input type="text" class="keys-translr__box-controls_search-text" name="search-id" placeholder="Поиск по id"/>
                                <button type="submit" class="keys-translr__box-controls_search-btn">
                                    &#10148;
                                </button>
                            </form>
                        </div>
                        <div class="keys-translr__box-controls_filter fai-c mb-30">
                            <div class="fai-c fjc-sb">
                                <div>
                                    <a href="/" class="keys-translr__box-controls_filter-all">Все</a>
                                </div>
                                <div class="pl-20">
                                    <a href="/?filter-open=10" class="keys-translr__box-controls_filter-open">Открытые</a>
                                </div>
                                <div class="pl-20">
                                    <a href="/?filter-close=10" class="keys-translr__box-controls_filter-close">Закрытые</a>
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
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>

                                <?php if ($searchId === $arItemSearch['ID']): ?>
                                    <tr>
                                        <td><?= $arItemSearch['ID'] ?></td>
                                        <td>
                                            <a href="/?ELEMENT_ID=<?= $arItemSearch['ID'] ?>" class="keys-translr__box-req-link">
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

                            <? endforeach; ?>

                        <?php endif; ?>



                        <?php if (isset($filterOpen) && !empty($filterOpen)): ?>

                            <?
                            // По фильтру (открытые)
                            foreach ($arResult["ITEMS"] as $arItemOpen): ?>

                                <?php
                                // Выводим только закрытые
                                if ($arItemOpen['PROPERTIES']['STATUS']['VALUE_ENUM_ID'] != 10): ?>
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

                            <? endforeach; ?>

                        <?php endif; ?>



                        <?php if (isset($filterClose) && !empty($filterClose)): ?>

                            <?
                            // По фильтру (закрытые)
                            foreach ($arResult["ITEMS"] as $arItemClose): ?>

                                <?php
                                // Выводим только закрытые
                                if ($arItemClose['PROPERTIES']['STATUS']['VALUE_ENUM_ID'] == 10): ?>
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

                            <? endforeach; ?>

                        <?php endif; ?>




                        <?php

                        if (empty($searchId) && empty($filterClose) && empty($filterOpen)): ?>
                            <?

                            // Без поиска и фильтров
                            foreach ($arResult["ITEMS"] as $arItem): ?>

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

<?php else: ?>

    <div class="mt-60 pl-60 pr-60">
        <div class="fjc-c mb-30">
            <h1 class="color__black fs-28"><span class="status-str__danger">Доступ ограничен!</span></h1>
        </div>
    </div>

<?php endif; ?>