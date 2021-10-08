<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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



$arUserGroups = CUser::GetUserGroup($USER->GetID());
$findGroupAdmin = in_array("1", $arUserGroups);
$findGroupMFC = in_array("5", $arUserGroups);
$findGroupKeys = in_array("6", $arUserGroups);


$arFile = json_decode($arResult["PROPERTIES"]["ATTACHED_FILES_WITH_TRANSLATE"]["VALUE"], true);
$getFile = CFile::GetPath($arFile[0]);

?>

<div class="keys-translr__box_wrap-request">
    <!-- header -->
    <div class="keys-translr__box-header pt-20 pb-20 pl-60 pr-60 mb-60">
        <!-- humburger -->
        <div id="humburger-btn" class="humburger-wrap">
            <span class="humburger-line humburger-start"></span>
            <span class="humburger-line humburger-middle"></span>
            <span class="humburger-line humburger-end"></span>
        </div>
        <!-- humburger end -->

        <div class="fjc-s fai-c">
            <div>
                <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo-blue.svg" alt="logo-blue" />
            </div>
            <div class="pl-10 color__blue-light fs-20 fontw-700">
                Переводы
            </div>
        </div>
    </div>

    <div class="pl-60 pr-60">

        <div class="fjc-c mb-30 mt-30">
            <h3 class="color__black fs-28">
                <?= $arResult['NAME'] ?>
            </h3>
        </div>

        <div class="keys-translr__box-canvas__print">
            <div class="fjc-c mb-30">
                <a
                        href="#!"
                        id="screen"
                        class="
                    button__primary
                    color__white
                    pl-30
                    pr-30
                    pt-10
                    pb-10
                    fs-16
                  "
                >
                    <span class="mr-10">&#128438;</span> Распечатать</a
                >
            </div>
        </div>

        <div
                class="
                keys-translr__box-canvas__count-page
                fjc-c
                fai-c
                ffd-column
                fw-wrap
              "
        >
            <div class="mb-20 mb-20">
                <span class="fs-18 fontw-700">Количество страниц: <span id="page_count"></span></span>
            </div>
<!--            <div class="mb-20 mb-20">-->
<!--                <span class="fs-18 fontw-700">Текущая страница: <span>12</span></span>-->
<!--            </div>-->
        </div>

        <div class="mt-20 mb-20">
            <div class="keys-translr__box-canvas-wrap">
                <canvas id="the-canvas" class="mb-90"></canvas>
            </div>
        </div>
    </div>

    <div id="<?= $findGroupAdmin || $findGroupMFC || $findGroupKeys ? '' : 'keys-translr-2'  ?>">

        <div class="keys-translr__box-canvas__toggle fjc-c fai-c fw-wrap">
            <div class="mr-10 ml-10">
                <a href="#!" id="prev" class="keys-translr__box-canvas__toggle-prev"></a>
            </div>
            <div class="mr-10 ml-10">
                <a href="#!" id="next" class="keys-translr__box-canvas__toggle-next"></a>
            </div>
        </div>
    </div>

</div>

<script>

    // window.oncontextmenu = function () {return false;}
    // window.onkeydown = function () {return false;}

    var url = '<?= $getFile ?>';

    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window['pdfjs-dist/build/pdf'];

    // The workerSrc property shall be specified.
    // pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
    pdfjsLib.GlobalWorkerOptions.workerSrc = '/local/templates/.default/js/plugins/pdf.worker.js';

    var pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1.3,
        canvas = document.getElementById('the-canvas'),
        ctx = canvas.getContext('2d');

    /**
     * Get page info from document, resize canvas accordingly, and render page.
     * @param num Page number.
     */
    function renderPage(num) {
        pageRendering = true;
        // Using promise to fetch the page
        pdfDoc.getPage(num).then(function(page) {
            var viewport = page.getViewport({scale: scale});
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);

            // Wait for rendering to finish
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        // Update page counters
        document.getElementById('page_num').textContent = num;
    }

    /**
     * If another page rendering in progress, waits until the rendering is
     * finised. Otherwise, executes rendering immediately.
     */
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /**
     * Displays previous page.
     */
    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    }
    document.getElementById('prev').addEventListener('click', onPrevPage);

    /**
     * Displays next page.
     */
    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
    }
    document.getElementById('next').addEventListener('click', onNextPage);

    /**
     * Asynchronously downloads PDF.
     */
    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('page_count').textContent = pdfDoc.numPages;

        // Initial/first page rendering
        renderPage(pageNum);
    });

    function printCanvas()
    {
        var dataUrl = document.getElementById('the-canvas').toDataURL(); //attempt to save base64 string to server using this var
        let windowContent = '<!DOCTYPE html>';
        windowContent += '<html>';
        windowContent += '<head><title>Print canvas</title></head>';
        windowContent += '<body>';
        windowContent += '<img src="' + dataUrl + '">';
        windowContent += '</body>';
        windowContent += '</html>';

        const printWin = window.open('', '', 'width=' + screen.availWidth + ',height=' + screen.availHeight);
        printWin.document.open();
        printWin.document.write(windowContent);

        printWin.document.addEventListener('load', function() {
            printWin.focus();
            printWin.print();
            printWin.document.close();
            // printWin.close();
        }, true);
    }

    document.querySelector('#screen').addEventListener('click', function () {
        printCanvas();
    })

</script>
