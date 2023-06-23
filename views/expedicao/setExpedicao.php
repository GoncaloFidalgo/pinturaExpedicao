<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
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
        <?= Html::button('Picar', ['class' => 'button', 'id' => 'btn-picar']) ?>
    </div>
</div>
<?= Dialog::widget(); ?>

<script>
    $(document).ready(function () {
        var dropdown = $('#dropdown-expedicoes');
        var expedicoes = <?= json_encode($data) ?>;
        var obra = $('#label-obra');
        var empresa = $('#item2');

        var expedicao = JSON.parse(window.sessionStorage.getItem('expedicao'));
        console.log(expedicao);
        try {
            if (expedicao){
                let index;
                expedicoes.find((o, i) => {
                    if (o.N_Expedicao === expedicao.N_Expedicao) {
                        index = i;
                        return true;
                    }
                });

                var dropdown1 = document.getElementById("dropdown-expedicoes");
                dropdown1.value = index;
                obra.text("OB "+expedicoes[dropdown.val()].N_Obra);
                empresa.text(expedicoes[dropdown.val()].Empresa);
            }
        }catch (e) {
            console.log(e);
            window.sessionStorage.removeItem('expedicao');
        }


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
                krajeeDialog.confirm("Picar para a expedição: " + expedicao.N_Expedicao, function (result) {
                    if (result) {
                        console.log(expedicao)
                        window.sessionStorage.setItem('expedicao',JSON.stringify(expedicao));
                        window.location.href = 'picagem';
                    }
                });
            }
        });

    });
</script>
