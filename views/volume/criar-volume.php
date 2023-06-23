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
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="info">
            <?php Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php endif; ?>
    <div class="leitura">
        <div style="align-self: center; font-size: 20px">Obra</div>

        <?php
        if (isset($num_obra)) {
            echo Html::input('number', 'input-obra', $num_obra, ['id' => 'input-obra']);
        } else {
            echo Html::input('number', 'input-obra', '', ['id' => 'input-obra']);
        }
        //LEIRIA
        //MARINHA GRANDE
        ?>

        <?= Html::button('Ok', ['id' => 'btn-ok', 'class' => 'button']) ?>
    </div>

    <div style="margin-top: 20px; display: flex; align-items: center; justify-content: space-between; padding-bottom: 10px">
        <div>
            <label for="local"> Local:</label>
            <select name="Local" id="local" style="width: 111px;">
                <option value="Leiria">Leiria</option>
                <option value="Grande">Marinha Grande</option>
            </select>
        </div>


        <div style="align-items: center;display: flex;">

            <?= Html::button('Criar Volume', ['id' => "btn-criar-volume", 'class' => 'button float-end', 'style' => 'width: 111px']) ?>
        </div>
    </div>

    <div style="display:flex;">
        <span style="font-size: 20px">Lista Volumes</span>

    </div>

    <?php
    if (isset($lista)) {
        echo GridView::widget([
            'dataProvider' => $lista,
            'id' => 'lista_volumes',
            'layout' => "{items}{summary}",
            'tableOptions' => [
                'style' => 'margin-top: 10px',
                'class' => 'table table-striped table-bordered',
            ],
            'columns' => [

                [
                    'value' => 'NumeroVolume',
                    'label' => 'Nº Volume',
                ],
            ],

        ]);
    }


    ?>

    <?= Dialog::widget(); ?>
    <div style="margin-bottom: 100px"></div>
</div>

<script>
    const input_obra = $('#input-obra');
    const lista_volumes = $('#lista_volumes');
    const btn_criar_volume = $('#btn-criar-volume');
    const select = document.getElementById('local');

    btn_criar_volume.click(function () {
        var local = select.options[select.selectedIndex].text;
        if (input_obra.val() !== '') {
            krajeeDialog.confirm("Deseja criar o volume ? \n" + "Obra: " + input_obra.val() + '\nLocal: ' + local, function (result) {
                if (result) {
                    $.ajax({
                        url: '../soap/inserir-volume-id',
                        type: "get",
                        data: {
                            num_obra: input_obra.val(),
                            Utilizador: <?= Yii::$app->user->getId()?>,
                            Local: local,
                        },
                        success: function (response) {
                            swal.fire(
                                'Sucesso!',
                                'Volume ' + response + ' criado com sucesso',
                                'success',
                            ).then(function () {
                                window.location = 'get-lista?num_obra=' + input_obra.val();
                            });

                        },
                        error: function (xhr) {
                            result = false;
                        }
                    });
                }
            });
        } else {
            swal.fire(
                'Atenção!',
                'Introduza uma obra',
                'error',
            )
        }


    })


    input_obra.on("keyup", function (event) {
        if (event.key === 'Enter') {
            if (input_obra.val() === '') {
                swal.fire(
                    'Atenção!',
                    'Introduza o número da obra',
                    'error',
                );
            } else {
                window.location = 'get-lista?num_obra=' + input_obra.val();
            }
        }
    });

    $('#btn-ok').click(function () {
        if (input_obra.val() === '') {
            swal.fire(
                'Atenção!',
                'Introduza o número da obra',
                'error',
            );
        } else {
            window.location = 'get-lista?num_obra=' + input_obra.val();
        }
    });
</script>