<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Listagem';
$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);
$soap = new \app\models\Soap();
?>
<div class="site-index">
    <?= Html::button('Lista', ['id' => "btn-lista", 'class' => 'button']) ?>

    <?php
    if (isset($lista)){
        echo GridView::widget([
            'dataProvider' => $lista,
            'layout'=> "{items}{summary}",
            'tableOptions' => [
                'style' => 'margin-top: 10px',
                'class' => 'table table-striped table-bordered',
            ],
            'columns' => [
                'Referencia',
                'Quantidade',
            ],
        ]);
    }
 ?>
<div style="margin-bottom: 100px"></div>
</div>
<script>
    var expedicao = JSON.parse(window.sessionStorage.getItem('expedicao'));

    $('#btn-lista').click(function () {
        if (expedicao){
            window.location = 'madeira?numeroExpedicao=' + expedicao.N_Expedicao;
        }else {
            swal.fire(
                'Atenção!',
                'Escolha uma expedição',
                'error',
            ).then(function () {
                window.location.replace("../expedicao/set-expedicao");
            });
        }

    });
</script>