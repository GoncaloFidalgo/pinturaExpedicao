<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
AppAsset::register($this);
?>
<?php $this->beginPage();
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/images/poco.png')]);
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100" translate="no">
    <head>
        <meta name="google" content="notranslate"/>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <main role="main">
        <div class="container">
            <?= $content ?>
        </div>
    </main>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
