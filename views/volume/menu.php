<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Volume';
?>
<div class="site-index">
    <div class="menu-opcoes">
        <?= Html::a('Criar Volume', 'volume/criar-volume', ['class' => 'button'])?>
        <?= Html::a('Encher Volume', 'volume/encher-volume',['class' => 'button'])?>
        <?= Html::a('Pesquisar', ['volume/pesquisar-volume', 'DesIdPai' => 0],['class' => 'button'])?>
        <?= Html::a('Sair', 'site',['class' => 'button'])?>
    </div>
</div>
