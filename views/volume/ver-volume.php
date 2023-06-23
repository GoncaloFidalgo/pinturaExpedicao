<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Volume';

$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);

?>
<div class="site-index">



    <div style="font-size: 20px">  <?= 'Conteúdo do volume '. $NumVolume . ' da Obra ' . $NumObra?></div>

    <?php
    if (isset($lista)) {
        echo GridView::widget([
            'dataProvider' => $lista,
            'id' => 'lista_volumes',
            'summary' => '',
            'tableOptions' => [
                'style' => 'margin-bottom: 100px',
                'class' => 'table table-striped table-bordered',
            ],
            'columns' => [
                [
                    'value' => 'referencia',
                    'label' => 'Ref',
                ],
                [
                    'value' => 'nome',
                    'label' => 'Nome',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Apagar',
                    'template' => '{delete}',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center; vertical-align: middle', 'class' => 'fa-2x'],
                    'urlCreator' => function ($action, $model, $key) use ($NumObra, $NumVolume){
                        if ($action == "delete") {
                            return Url::to(['apagar-linha', 'referencia' => $model->referencia, 'NumObra' => $NumObra, 'NumVolume' => $NumVolume]);
                        }
                    }
                ],

            ],
        ]);
    }

    // 'encher-volume',
    ?>
<div class="footer-buttons">
    <?= Html::button('Voltar', ['class' => 'button', 'id' => 'btn-voltar'])?>
</div>




</div>
<?= Dialog::widget(); ?>
<script>
$('#btn-voltar').click(function () {
    window.location = 'encher-volume';
});
</script>