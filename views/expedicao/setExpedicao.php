<?php

/** @var yii\web\View $this */

use kartik\select2\Select2;
use yii\bootstrap5\Html;

$this->title = 'Inicio';
$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);

?>
<div class="site-index">
    <div class="content-top">
        <?= Select2::widget([
            'name' => 'dropdown-expedicoes',
            'hideSearch' => true,
            'data' => $lista_expedicoes,
            'options' => [
                'placeholder' => 'Expedições',
                'id' => 'dropdown-expedicoes',
            ],
        ]);
        ?>
    </div>
    <div class="content-center">
        <ul>
            <li id="label-obra"></li>
            <li id="item2"></li>
        </ul>
    </div>
    <div class="content-bottom">
        <?= Html::button('Picar', ['class' => 'button', 'id' => 'btn-picar']) ?>
    </div>
</div>


<script>
    $(document).ready(function () {
        var dropdown = $('#dropdown-expedicoes');
        var expedicoes = <?= json_encode($data) ?>;
        var obra = $('#label-obra');
        var empresa = $('#item2');
        dropdown.select2({}).on("select2:select", function (e) {
            obra.text("OB "+expedicoes[dropdown.val()].N_Obra);
            empresa.text(expedicoes[dropdown.val()].Empresa);
        });
        $('#btn-picar').click(function(){
            if (dropdown.val() === ''){
                Swal.fire(
                    'Erro',
                    'Selecione uma expedição!',
                    'error'
                )
            }else {
                var expedicao = expedicoes[dropdown.val()];
                console.log(expedicao)
                window.sessionStorage.setItem('expedicao',JSON.stringify(expedicao));
                window.location.href = 'picagem';
            }
        });
    });



</script>
