<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Expedição';
?>
<div class="site-index">
    <div class="buttons-top">
        <?= Html::a('Expedição', 'expedicao/set-expedicao', ['class' => 'button'])?>
        <?= Html::a('Volumes', '',['class' => 'button'])?>
    </div>
    <div class="buttons-center">
        <?= Html::a('Pintura', '',['class' => 'button'])?>
    </div>
</div>
