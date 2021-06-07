<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Сервис обмена переводами между организациями");

?>

    <?php if($findGroupAdmin || $findGroupMFC || $findGroupKeys):
        header('Location: /list/');
    ?>

    <?php else: ?>

    <div id="keys-translr">
        <section class="keys-translr__auth">
            <div class="keys-translr__auth_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="keys-translr__auth-box">
                                <a href="/">
                                    <img src="<?= DEFAULT_TEMPLATE_PATH ?>/img/logo/logo.svg" alt="logo">
                                </a>
                                <div class="mb-20 mt-20">
                                    <h3 class="fs-24 color__white">Авторизация</h3>
                                </div>
                                <div>
                                    <form class="keys-translr__auth_form" id="login-form" action="/ajax/auth/login/" method="post">
                                        <div class="fjc-c ffd-column">
                                            <input class="keys-translr__auth_form-login fs-16" type="text" name="login" placeholder="Введите логин">
                                        </div>
                                        <div class="fjc-c ffd-column">
                                            <input class="keys-translr__auth_form-pass fs-16" type="password" name="password" placeholder="Введите пароль">
                                        </div>
                                        <div class="fjc-c mt-20 pt-5">
                                            <button type="submit" class="button__primary color__white pl-60 pr-60 pt-10 pb-10 fs-16">Войти</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php endif; ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>