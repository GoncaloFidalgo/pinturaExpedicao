<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use kartik\dialog\Dialog;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'id' => 'navbarpoco',
        'options' => ['class' => 'navbar-expand fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'POST', ['id' => 'form-logout'])
                . Html::button(
                    'Logout',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        <div class="footer-buttons">
            <?= Html::button('Expedição', ['class' => 'button', 'id' => 'id-set-expedicao']) ?>
            <?= Html::button('Picagem', ['class' => 'button', 'id' => 'id-picagem']) ?>
            <?= Html::button('Listagem', ['class' => 'button', 'id' => 'id-listagem']) ?>
            <?= Html::button('Madeira', ['class' => 'button', 'id' => 'id-madeira']) ?>
        </div>
    </div>

</main>

<?= Dialog::widget(); ?>
<footer id="footer" class="mt-auto py-3">

</footer>

<?php $this->endBody() ?>
</body>
</html>
<script>
    var expedicao = JSON.parse(window.sessionStorage.getItem('expedicao'));
    const btn_listagem = document.querySelector('#id-listagem');
    const btn_madeira = document.querySelector('#id-madeira');
    const btn_picagem = document.querySelector('#id-picagem');
    const btn_set_expedicao = document.querySelector('#id-set-expedicao');

    btn_set_expedicao.addEventListener('click', function (event) {
        window.location = 'set-expedicao';
    });

    btn_listagem.addEventListener('click', function (event) {
        if (!expedicao) {
            swal.fire(
                'Atenção!',
                'Escolha uma expedição',
                'error',
            );

        } else {
            window.location = 'listagem?n_expedicao=' + expedicao.N_Expedicao;
        }
    });

    btn_picagem.addEventListener('click', function (event) {
        if (!expedicao) {
            swal.fire(
                'Atenção!',
                'Escolha uma expedição',
                'error',
            );

        } else {
            window.location = 'picagem?n_expedicao=' + expedicao.N_Expedicao;
        }
    });

    btn_madeira.addEventListener('click', function (event) {
        if (!expedicao) {
            swal.fire(
                'Atenção!',
                'Escolha uma expedição',
                'error',
            );

        } else {
            window.location = 'madeira?numeroExpedicao=' + expedicao.N_Expedicao;
        }
    });

    $('.logout').click(function () {
        krajeeDialog.confirm("Tem a certeza que quer sair da aplicação?", function (result) {
            if (result) {
                sessionStorage.clear();
                $('#form-logout').submit();
            } else {

            }
        });
    });

</script>
<?php $this->endPage() ?>
