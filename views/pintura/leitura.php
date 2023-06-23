<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Html;

$this->title = 'Pintura';

$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);
?>
<div class="site-index">
    <div id="lb_operacao" style="font-weight: bold"><?= $tipo ?></div>
    <div style="font-weight: bold">Funcionário</div>
    <div class="leitura">
        <?= Html::input('text', 'input-funcionario', '', ['id' => 'input-funcionario']) ?>

        <?= Html::button('Ok', ['id' => 'btn-ok-funcionario', 'class' => 'button']) ?>
    </div>

    <div id="lb_nome" style="font-weight: bold">xxxx</div>
    <div style="font-weight: bold">Lote</div>
    <div class="leitura">
        <?= Html::input('text', 'input-lote', '', ['id' => 'input-lote', 'style' => 'width: 100%']) ?>
        <?= Html::button('Ok', ['id' => 'btn-ok-lote', 'class' => 'button']) ?>
    </div>
    <div style="font-weight: bold">Peça</div>

    <div class="leitura">
        <?= Html::input('text', 'input-peca', '', ['id' => 'input-peca', 'style' => 'width: 100%;']) ?>

        <?= Html::button('Ok', ['id' => 'btn-ok-peca', 'class' => 'button']) ?>
    </div>
    <div id="lb_ultima_peca" style="font-weight: bold ;margin-bottom: 20px">----</div>

    <?= Html::button('Sair', ['class' => 'button', 'onclick'=>"window.location = '../pintura'"]) ?>

</div>
<?= Dialog::widget(); ?>
<script>
    // LABELS
    const lb_operacao = document.querySelector('#lb_operacao');
    const lb_nome = document.querySelector('#lb_nome');
    const lb_ultima_peca = document.querySelector('#lb_ultima_peca')

    // INPUTS
    const input_funcionario = document.querySelector('#input-funcionario');
    const input_lote = document.querySelector('#input-lote');
    const input_peca = document.querySelector('#input-peca');

    // BUTTONS
    const btn_ok_funcionario = document.querySelector('#btn-ok-funcionario');
    const btn_ok_peca = document.querySelector('#btn-ok-peca');
    const btn_ok_lote = document.querySelector('#btn-ok-lote');

    // VARIABLES
    let n_func = 0;
    let inserir_pintura_url = '';
    let SerialNumber = 0;
    let QtdTotal = 0;
    let DesIdPai = 0;
    let SerialNumberAnterior = 0;
    let QtdTotalAnterior = 0;
    let DesIdPaiAnterior = 0;

    // EVENTS

    /** Depois da página carregar **/
    window.addEventListener("load", (event) => {
        input_funcionario.focus();
        switch (lb_operacao.textContent) {
            case "PRIMARIO":
                inserir_pintura_url = '../soap/inserir-primario';
                break;
            case "INTERMEDIO":
                inserir_pintura_url = '../soap/inserir-intermedio';
                break;
            case "ACABAMENTO":
                inserir_pintura_url = '../soap/inserir-acabamento';
                break;
        }
    });

    /** Ao pressionar no botão OK do input do funcionario **/
    btn_ok_funcionario.addEventListener("click", () => {
        trataFuncionario();
    });

    /** Ao pressionar 'ENTER' no input do funcionario **/
    input_funcionario.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            trataFuncionario();
        }
    });

    /** Ao pressionar o botão OK do input da peca **/
    btn_ok_peca.addEventListener("click", () => {
        if (n_func === 0) {
            Swal.fire(
                'Atenção!',
                'Funcionário não definido',
                'error'
            ).then(function () {
                setTimeout(() => input_funcionario.focus(), 300);
                input_funcionario.select();
            });
        } else {
            if (input_lote.value === '') {
                Swal.fire(
                    'Atenção!',
                    'Insira o lote',
                    'error'
                ).then(function () {
                    setTimeout(() => input_lote.focus(), 300);
                    input_lote.select();
                });
            } else {
                lb_ultima_peca.textContent = input_peca.value;
                trataPintura();
            }

        }
    });

    /** Ao pressionar 'ENTER' no input da peca **/
    input_peca.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            if (n_func === 0) {
                Swal.fire(
                    'Atenção!',
                    'Funcionário não definido',
                    'error'
                ).then(function () {
                    setTimeout(() => input_funcionario.focus(), 300);
                    input_funcionario.select();
                });
            } else {
                if (input_lote.value === '') {
                    Swal.fire(
                        'Atenção!',
                        'Insira o lote',
                        'error'
                    ).then(function () {
                        setTimeout(() => input_lote.focus(), 300);
                        input_lote.select();
                    });
                } else {
                    lb_ultima_peca.textContent = input_peca.value;
                    trataPintura();
                }

            }
        }
    });

    /** Ao pressionar 'ENTER' no input do lote **/
    input_lote.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            if (input_lote.value === '') {
                Swal.fire(
                    'Atenção!',
                    'Insira o lote',
                    'error'
                );
            } else {
                const loteExiste = ajaxGet('../soap/lote-existe', {lote: input_lote.value}, false);
                if (loteExiste) {
                    input_peca.focus();
                } else {
                    Swal.fire(
                        'Atenção!',
                        'Lote não existe',
                        'error'
                    );
                }
            }


        }
    });

    /** Ao pressionar o botão OK do input do lote **/
    btn_ok_lote.addEventListener("click", () => {
        input_peca.focus();
    });

    // FUNCTIONS

    /** Insere a pintura **/
    function trataPintura() {
        let data = {
            NumeroFuncionario: n_func,
            Lote: input_lote.value,
            referencia: input_peca.value,
        };
        const inserirPintura = ajaxGet(inserir_pintura_url, data, false);
        console.log(inserirPintura);
        if (inserirPintura.SerialNumber === 0){
            Swal.fire(
                'Erro!',
                'A pintura já está inserida!',
                'error'
            ).then(function () {
                setTimeout(() => input_peca.focus(), 300);
                input_peca.select();
            })
        }else {
            if (inserirPintura.result) {
                Swal.fire(
                    'Sucesso!',
                    'Pintura adicionada com sucesso',
                    'success'
                ).then(function () {
                    setTimeout(() => input_peca.focus(), 300);
                    input_peca.select();

                    SerialNumber = inserirPintura.SerialNumber;
                    QtdTotal = inserirPintura.QtdTotal;
                    DesIdPai = inserirPintura.DesIdPai;

                    if (DesIdPaiAnterior !== '') {
                        if (DesIdPai === DesIdPaiAnterior && QtdTotalAnterior === SerialNumber) {
                            krajeeDialog.confirm("Detectada leitura da primeira e da última etiqueta, deseja importar todas ?", function (result) {
                                if (result) {
                                    const lista = ajaxGet('../soap/get-pecas-id-pais-serail', {DesidPai: DesIdPai}, false);
                                    for (const [i, item] of lista.int.entries()) {
                                        let data = {
                                            NumeroFuncionario: n_func,
                                            Lote: input_lote.value,
                                            referencia: item,
                                        };
                                        const inserirPintura = ajaxGet(inserir_pintura_url, data, false);
                                        SerialNumber = inserirPintura.SerialNumber;
                                        QtdTotal = inserirPintura.QtdTotal;
                                        DesIdPai = inserirPintura.DesIdPai;
                                    }
                                    Swal.fire(
                                        'Sucesso!',
                                        'Pinturas adicionadas com sucesso',
                                        'success',
                                    ).then(function () {
                                        setTimeout(() => input_peca.focus(), 300);
                                        input_peca.select();
                                    });
                                }
                            });
                        }
                    }
                });
                DesIdPaiAnterior = DesIdPai;
                SerialNumberAnterior = SerialNumber;
                QtdTotalAnterior = QtdTotal;

            } else {
                Swal.fire(
                    'Atenção!',
                    'Ocorreu um erro ao adicionar a referência ' + input_peca.value,
                    'error'
                );
            }
            input_peca.textContent = '';
            input_peca.focus();
        }

    }

    /** Obtém o funcionário **/
    function trataFuncionario() {
        if (input_funcionario.value.includes("F")) {
            n_func = parseInt(input_funcionario.value.split('-')[1]);
        } else {
            n_func = parseInt(input_funcionario.value);
        }

        const getFuncionario = ajaxGet('../soap/get-funcionario', {Numero: n_func}, false);
        if (getFuncionario === '') {
            Swal.fire(
                'Atenção!',
                'Numero de funcionario incorreto',
                'error'
            ).then(function () {
                setTimeout(() => input_funcionario.focus(), 300);
                input_funcionario.select();
            });
        } else {
            lb_nome.textContent = getFuncionario;
            input_lote.focus();
        }

    }

    /** Efetua pedidos ajax **/
    function ajaxGet(url, data, async) {
        var result;
        $.ajax({
            url: url,
            type: "get",
            async: async,
            data: data,
            success: function (response) {
                result = JSON.parse(response);
            },
            error: function (xhr) {
                result = false;
            }
        });
        return result;
    }
</script>
