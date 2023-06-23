<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Volume';

$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);

?>
<div class="site-index">

    <div class="leitura">
        <?php
        if (isset($desIdPai)) {
            echo Html::input('text', 'input-des-id-pai', $desIdPai, ['id' => 'input-des-id-pai']);
        } else {
            echo Html::input('text', 'input-des-id-pai', '', ['id' => 'input-des-id-pai']);
        }
        ?>
        <?= Html::button('Ok', ['id' => 'btn-ok', 'class' => 'button']) ?>
    </div>
    <?php
   /*
        ["AUTO_ID"]=>
        int(3399)
        ["NUMERO_VOLUME"]=>
        int(70)
        ["REFERENCIA"]=>
        string(7) "1752908"
        ["NUMERO_LISTA"]=>
        int(2105)
        ["SUB_OBRA"]=>
        float(2532.01)
        ["AUTO_ID_VOLUME"]=>
        int(110)
        ["NUM_OBRA"]=>
        int(2532)
        ["DES_SERIAL_NUMBER"]=>
        string(25) "2532.01-C0704.2687-23-0#1"
        ["DES_ID_PAI"]=>
        string(23) "2532.01-C0704.2687-23-0"
        ["DES_PESO"]=>
        string(4) "2.38"
        ["DATA_HORA"]=>
        NULL
          */
    if (isset($lista)) {
        echo GridView::widget([
            'dataProvider' => $lista,
            'id' => 'lista_volumes',
            'layout'=> "{items}{summary}",
            'tableOptions' => [
                'style' => 'margin-top: 10px',
                'class' => 'table table-striped table-bordered',
            ],
            'columns' => [
                [
                    'value' => 'NUMERO_VOLUME',
                    'label' => 'Vol.',
                ],
                [
                    'value' => 'DES_SERIAL_NUMBER',
                    'label' => 'Nome',
                ],
                [
                    'value' => 'REFERENCIA',
                    'label' => 'Referencia',
                ],
            ],
        ]);
    }


    ?>

    <div style="margin-bottom: 100px"></div>
</div>
<?= Dialog::widget(); ?>
<script>
    const input_des_id_pai = $('#input-des-id-pai');
    const lista_volumes = $('#lista_volumes');

    $('#btn-ok').click(function () {
        if (input_des_id_pai.val() === '') {
            swal.fire(
                'Atenção!',
                'Introduza um valor',
                'warning',
            );
        } else {
            window.location = 'pesquisar-volume?DesIdPai=' + input_des_id_pai.val();
        }
    });
</script>