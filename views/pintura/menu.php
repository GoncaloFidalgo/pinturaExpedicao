<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Pintura';
?>
<div class="site-index">
    <div class="menu-opcoes">
        <?= Html::a('Primário', ['leitura', 'tipo' => 'PRIMARIO'], ['class' => 'button'])?>
        <?= Html::a('Intermédio', ['leitura', 'tipo' => 'INTERMEDIO'],['class' => 'button'])?>
        <?= Html::a('Acabamento', ['leitura', 'tipo' => 'ACABAMENTO'],['class' => 'button'])?>
        <?= Html::a('Sair', 'site',['class' => 'button'])?>
    </div>
</div>
