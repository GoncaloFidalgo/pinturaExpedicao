<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Html;
use yii\bootstrap5\Progress;


$this->title = 'Picagem';
$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);
?>
<div class="site-index">
    <div class="content">
        <ul>
            <li id="label-obra"></li>
        </ul>
        <div class="leitura">
            <?= Html::input('number', 'input-codigo-barras', '', ['id' => 'input-codigo-barras']) ?>
            <?= Html::button('Ok', ['id' => 'btn-ok']) ?>
        </div>
        <div class="div-2000">
            <?= Html::input('number', 'input-volume', '', ['id' => 'input-volume']) ?>
            <?= Html::button('Limpar', ['id' => 'btn-limpar']) ?>
            <?= Html::button('2000', ['id' => 'btn-2000']) ?>
        </div>
        <ul>
            <li id="label_ultima_leitura"></li>
            <li id="label_desIdPai"></li>
        </ul>
        <div class="status"></div>
        <div class="progress-bar">
            <div id="progress" class="progress"></div>
        </div>
        <ul>
            <li id="total-kilos"></li>
        </ul>
    </div>
</div>
<?= Dialog::widget() ?>
<script>
    const label_obra = $('#label-obra');
    const input_codigo_barras = $('#input-codigo-barras');
    const progressElement = document.getElementById('progress');
    const input_volume = $('#input-volume');
    const label_total_kilos = $('#total-kilos');
    const status = $('.status');
    const label_desIdPai = $('#label_desIdPai');
    const label_ultima_leitura = $('#label_ultima_leitura');
    const btn_2000 = $('#btn-2000');
    var maxValue = 0;
    var des_id_pai_anterior = '';
    var SerialNumber, QtdTotal, DesIdPai, qtdTotalPecasPaiAnterior, serialNumberAnterior;

    var ajaxRequests = [];

    var expedicao = JSON.parse(window.sessionStorage.getItem('expedicao'));

    if (expedicao) {
        console.log(expedicao)
        label_obra.text(expedicao.N_Expedicao);
    }

    $('#btn-ok').click(function () {
        validaLeitura();
    });
    btn_2000.click(function () {
        input_volume.val(2000);
    });
    $('#btn-limpar').click(function () {
        input_volume.val('');
    });

    function validaLeitura() {
        // VOLUME_EXPEDIDO: 2550;1
        // VOLUME_NAO_EXPEDIDO: 2570;31
        let NumObra = 0;
        let NumVolume = 0;
        let input_value = input_codigo_barras.val().toString();
        let stop = false;
        var pecas = [];

        if (input_value.charAt(0) === '0') {
            if (input_value.length === 8) {
                NumObra = parseInt(input_value.substring(0, 5));
                NumVolume = parseInt(input_value.substring(6, 8));
                console.log(NumObra);
                console.log(NumVolume);
                //VOLUME_EXPEDIDO
                $.ajax({
                    url: "../soap/volume-expedido",
                    type: "get",
                    async: false,
                    data: {
                        NumObra: NumObra,
                        NumVolume: NumVolume,
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response) {
                            NumObra = 0;
                            NumVolume = 0;
                            Swal.fire(
                                'Atenção!',
                                'Este volume já foi expedido.',
                                'error'
                            );
                            stop = true;
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
                if (stop) {
                    return;
                }

                //VOLUME_EXISTE
                $.ajax({
                    url: "../soap/volume-existe",
                    type: "get",
                    data: {
                        NumObra: NumObra,
                        NumVolume: NumVolume,
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        if (!response) {
                            NumObra = 0;
                            NumVolume = 0;
                            Swal.fire(
                                'Atenção!',
                                'O Volume não existe',
                                'error'
                            );
                            stop = true;
                        }
                    },
                    error: function (xhr) {
                        //Do Something to handle error
                    }
                });
                if (stop) {
                    return;
                }
            }
            $.ajax({
                url: "../soap/get-lista-serial-number-volume",
                type: "get",
                async: false,
                data: {
                    NumObra: NumObra,
                    NumVolume: NumVolume,
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if (Object.keys(response).length === 0) {
                        Swal.fire(
                            'Erro!',
                            ' ',
                            'error'
                        );
                        stop = true;
                        return;
                    }
                    response = response.int;
                    maxValue = response.length;
                    pecas = response;
                    for (const [i, item] of pecas.entries()) {
                        var request = $.ajax({
                            url: "../soap/set-serial-number",
                            type: "get",
                            //async: false,
                            data: {
                                item: item,
                                utilizador: <?= Yii::$app->user->identity->getId()?>,
                                expedicao: expedicao.N_Expedicao,
                                NumVolume: NumVolume,
                                boolean: true,
                            },
                            success: function (response) {
                                response = JSON.parse(response);
                                let a = response.split('-');

                                if (a[0] === '4') {
                                    Swal.fire(
                                        'Erro!!',
                                        ' ',
                                        'error'
                                    );
                                    abortPendingRequests();
                                    stop = true;
                                } else {
                                    updateProgressBar(i + 1);
                                }

                            },
                            error: function (xhr) {
                                //Do Something to handle error
                            }
                        });
                        ajaxRequests.push(request);
                    }
                    setTimeout(
                        function () {
                            if (stop) {
                                return;
                            }
                            $.ajax({
                                url: "../soap/set-volume-expedido",
                                type: "get",
                                //async: false,
                                data: {
                                    NumObra: NumObra,
                                    NumVolume: NumVolume,
                                },
                                success: function (response) {
                                    response = JSON.parse(response);
                                    if (response) {
                                        Swal.fire(
                                            'Concluido!',
                                            'Volume expedido.',
                                            'success'
                                        ).then(function () {
                                            progressElement.style.width = `0%`;
                                        });

                                    }
                                },
                                error: function (xhr) {
                                    //Do Something to handle error
                                }
                            });
                        }, 2000);
                },
                error: function (xhr) {
                    //Do Something to handle error
                }
            });
            if (stop) {
                return;
            }

        } else {
            var dados = [];
            var volume = 2000;
            var a = '';
            if (input_volume.val() !== '') {
                volume = parseInt(input_volume.val());
            }
            console.log(volume)
            $.ajax({
                url: "../soap/set-serial-number-new-version",
                type: "get",
                async: false,
                data: {
                    serialNumber: input_value,
                    utilizador: <?= Yii::$app->user->identity->getId()?>,
                    expedicao: expedicao.N_Expedicao,
                    Volume: volume,
                    isVolume: false,
                },
                success: function (response) {
                    response = JSON.parse(response); // result, SerialNumber, QtdTotal, DesIdPai

                    a = response.result.split('-');
                    SerialNumber = response.SerialNumber;
                    QtdTotal = response.QtdTotal;

                    DesIdPai = response.DesIdPai;
                },
                error: function (xhr) {
                    //Do Something to handle error
                }
            });
            $.ajax({
                url: "../soap/get-peso",
                type: "get",
                //async: false,
                data: {
                    numeroExpedicao: expedicao.N_Expedicao,
                },
                success: function (response) {
                    response = JSON.parse(response);
                    label_total_kilos.text(response);
                },
                error: function (xhr) {
                    //Do Something to handle error
                }
            });
            //a[0] = 3;

            switch (a[0].toString()) {
                case '1':
                    status.css('background', '#ffcb00');
                    Swal.fire(
                        'Atenção!',
                        'Já carregado nesta expedição.',
                        'warning'
                    ).then(function () {
                        krajeeDialog.confirm("Deseja eliminar o registo desta expedição ?", function (result) {
                            if (result) {
                                $.ajax({
                                    url: "../soap/apagar-referencia-expedicao",
                                    type: "get",
                                    //async: false,
                                    data: {
                                        numExpedicao: expedicao.N_Expedicao,
                                        Referencia: input_value,
                                    },
                                    success: function (response) {
                                        response = JSON.parse(response);
                                        if (response) {
                                            Swal.fire(
                                                'Sucesso',
                                                'Registo eleminado com sucesso',
                                                'success'
                                            )
                                        } else {
                                            Swal.fire(
                                                'Erro',
                                                'Ocorreu um problema ao eliminar o registo da expedição.',
                                                'error',
                                            )
                                        }
                                    },
                                    error: function (xhr) {
                                        //Do Something to handle error
                                    },
                                });
                            } else {

                            }
                        });
                    });
                    break;
                case '2':
                    status.css('background', '#ff0000');
                    Swal.fire(
                        'Atenção!',
                        a[1],
                        'error'
                    );
                    break;
                case '3':
                    status.css('background', '#00a221');
                    $.ajax({
                        url: "../soap/get-dados",
                        type: "get",
                        data: {
                            serialNumber: input_value,
                        },
                        success: function (response) {
                            response = JSON.parse(response).split('|');

                            if (response) {
                                label_desIdPai.text(response[0]);
                                label_ultima_leitura.text(input_value + ' - ' + response[1]);
                            }
                        },
                        error: function (xhr) {
                            //Do Something to handle error
                        },
                    });

                    if (des_id_pai_anterior !== '') {
                        if (DesIdPai === des_id_pai_anterior && qtdTotalPecasPaiAnterior === SerialNumber) {
                            krajeeDialog.confirm("Detectada leitura da primeira e da última etiqueta, deseja importar todas?", function (result) {
                                if (result) {
                                    $.ajax({
                                        url: "../soap/get-pecas-id-pais",
                                        type: "get",
                                        //async: false,
                                        data: {
                                            NumObra: expedicao.N_Obra,
                                            desidpai: DesIdPai,
                                        },
                                        success: function (response) {
                                            response = JSON.parse(response);
                                            response = response.int;
                                            maxValue = response.length;
                                            if (response) {
                                                for (const [i, item] of response.entries()) {
                                                    var request = $.ajax({
                                                        url: "../soap/set-serial-number",
                                                        type: "get",
                                                        //async: false,
                                                        data: {
                                                            item: item,
                                                            utilizador: <?= Yii::$app->user->identity->getId()?>,
                                                            expedicao: expedicao.N_Expedicao,
                                                            NumVolume: NumVolume,
                                                            boolean: true,
                                                        },
                                                        success: function (response) {
                                                            response = JSON.parse(response);
                                                            let a = response.split('-');

                                                            if (a[0] === '4') {
                                                                Swal.fire(
                                                                    'Erro!!',
                                                                    ' ',
                                                                    'error'
                                                                );
                                                                abortPendingRequests();
                                                                stop = true;
                                                            } else {
                                                                $.ajax({
                                                                    url: "../soap/get-dados",
                                                                    type: "get",
                                                                    data: {
                                                                        serialNumber: input_value,
                                                                    },
                                                                    success: function (response) {
                                                                        response = JSON.parse(response).split('|');

                                                                        if (response) {
                                                                            label_desIdPai.text(response[0]);
                                                                            label_ultima_leitura.text(input_value + ' - ' + response[1]);
                                                                        }
                                                                    },
                                                                    error: function (xhr) {
                                                                        //Do Something to handle error
                                                                    },
                                                                });
                                                                updateProgressBar(i + 1);
                                                            }

                                                        },
                                                        error: function (xhr) {
                                                            //Do Something to handle error
                                                        }
                                                    });
                                                    ajaxRequests.push(request);
                                                }
                                                setTimeout(
                                                    function () {
                                                        if (stop) {
                                                            return;
                                                        }
                                                        Swal.fire(
                                                            'Concluido!',
                                                            ' ',
                                                            'success'
                                                        ).then(function () {
                                                            progressElement.style.width = `0%`;
                                                        });
                                                    }, 2000);
                                            }
                                        },
                                        error: function (xhr) {
                                            //Do Something to handle error
                                        },
                                    });

                                    des_id_pai_anterior = '';
                                    serialNumberAnterior = '';
                                    qtdTotalPecasPaiAnterior = '';
                                } else {

                                }
                            });
                        }
                    }

                    des_id_pai_anterior = DesIdPai;
                    serialNumberAnterior = SerialNumber;
                    qtdTotalPecasPaiAnterior = QtdTotal;
                    break;
                case '4':
                    status.css('background', '#ff0000');
                    Swal.fire(
                        'Erro',
                        ' ',
                        'error'
                    );
                    break;
                case '5':
                    status.css('background', '#ff0000');
                    Swal.fire(
                        'Atenção!',
                        a[1],
                        'error'
                    );
                    break;
            }
        }
    }

    function updateProgressBar(value) {
        const percentage = (value / maxValue) * 100;
        progressElement.style.width = `${percentage}%`;
    }

    function abortPendingRequests() {
        // Iterate through the array and abort each request
        $.each(ajaxRequests, function (index, request) {
            request.abort();
        });
        // Clear the array
        ajaxRequests = [];
    }


</script>
