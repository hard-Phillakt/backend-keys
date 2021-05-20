<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Сервис обмена переводами между организациями");

use src\models\App;

?>

    <section class="app-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news",
                        "request_main",
                        Array(
                            "ADD_ELEMENT_CHAIN" => "N",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "BROWSER_TITLE" => "-",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_NOTES" => "",
                            "CACHE_TIME" => "0",
                            "CACHE_TYPE" => "N",
                            "CHECK_DATES" => "Y",
                            "COMPONENT_TEMPLATE" => "request_main",
                            "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                            "DETAIL_DISPLAY_TOP_PAGER" => "N",
                            "DETAIL_FIELD_CODE" => array(0=>"",1=>"",),
                            "DETAIL_PAGER_SHOW_ALL" => "N",
                            "DETAIL_PAGER_TEMPLATE" => "",
                            "DETAIL_PAGER_TITLE" => "Страница",
                            "DETAIL_PROPERTY_CODE" => array(0=>"CUSTOMER_ID",1=>"CUSTOMER_FIO",2=>"CUSTOMER_PHONE",3=>"CUSTOMER_COMMENT",4=>"ATTACHED_FILES_FOR_TRANSLATE",5=>"ATTACHED_FILES_WITH_TRANSLATE",6=>"LINK_LIFE_DATE",7=>"GENERATED_LINK",8=>"FIELD_PRICE",9=>"STATUS",10=>"ID_CUSTOMER",11=>"TEXT_TRANSLATE",12=>"TRANSLATOR_ID"),
                            "DETAIL_SET_CANONICAL_URL" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DISPLAY_TOP_PAGER" => "N",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "IBLOCK_ID" => "1",
                            "IBLOCK_TYPE" => "request",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "LIST_FIELD_CODE" => array(0=>"",1=>"",),
                            "LIST_PROPERTY_CODE" => array(0=>"CUSTOMER_ID",1=>"CUSTOMER_FIO",2=>"CUSTOMER_PHONE",3=>"CUSTOMER_COMMENT",4=>"ATTACHED_FILES_FOR_TRANSLATE",5=>"ATTACHED_FILES_WITH_TRANSLATE",6=>"LINK_LIFE_DATE",7=>"GENERATED_LINK",8=>"FIELD_PRICE",9=>"STATUS",10=>"ID_CUSTOMER",11=>"TEXT_TRANSLATE",12=>"TRANSLATOR_ID"),
                            "MESSAGE_404" => "",
                            "META_DESCRIPTION" => "-",
                            "META_KEYWORDS" => "-",
                            "NEWS_COUNT" => "15",
                            "PAGER_BASE_LINK_ENABLE" => "N",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "0",
                            "PAGER_SHOW_ALL" => "N",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_TEMPLATE" => ".default",
                            "PAGER_TITLE" => "Новости",
                            "PREVIEW_TRUNCATE_LEN" => "",
                            "SEF_MODE" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "SET_STATUS_404" => "Y",
                            "SET_TITLE" => "N",
                            "SHOW_404" => "N",
                            "SORT_BY1" => "ACTIVE_FROM",
                            "SORT_BY2" => "SORT",
                            "SORT_ORDER1" => "DESC",
                            "SORT_ORDER2" => "ASC",
                            "STRICT_SECTION_CHECK" => "N",
                            "USE_CATEGORIES" => "N",
                            "USE_FILTER" => "N",
                            "USE_PERMISSIONS" => "N",
                            "USE_RATING" => "N",
                            "USE_REVIEW" => "N",
                            "USE_RSS" => "N",
                            "USE_SEARCH" => "N",
                            "USE_SHARE" => "N",
                            "VARIABLE_ALIASES" => array("SECTION_ID"=>"SECTION_ID","ELEMENT_ID"=>"ELEMENT_ID",)
                        )
                    );?>

                </div>
            </div>
        </div>
    </section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>