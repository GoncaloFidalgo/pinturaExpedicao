<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Volume';

$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);

?>
<div class="site-index">
    <span>Volume</span>

    <?= Html::input('text', 'input-volume', '', ['id' => 'input-volume']) ?>

    <div style="display:flex; justify-content: space-between">
        <div>
            Obra:
            <span id="lb_obra" style="font-weight: bold"></span>
        </div>
        <div>
            Palete
            <?= Html::checkbox('check-palete', false, ['id' => 'check-palete']) ?>
        </div>
    </div>

    <div style="display:flex; justify-content: space-between">
        <div>
            Volume:
            <span id="lb_volume" style="font-weight: bold"></span>
        </div>
        <div>
            Aros
            <span id="lb_aros" style="font-weight: bold">-</span>
        </div>
    </div>
    <div style="margin-top: 20px">Referencia</div>
    <div class="leitura" style="margin-bottom: 20px">
        <?= Html::input('text', 'input-referencia', '', ['id' => 'input-referencia']) ?>
        <?= Html::button('Ok', ['id' => 'btn-ok', 'class' => 'button']) ?>
    </div>
    <div id="lb_ultima_referencia" style="font-weight: bold"></div>
    <div style="display: flex; justify-content: space-between">
        <div>
            <span>Nº Peças: </span>
            <span id="lb_pecas" style="font-weight: bold">0</span>
        </div>

        <?= Html::button('Palete', ['id' => 'btn-palete', 'class' => 'button', 'style' => 'width: 85px']) ?>
    </div>


    <div style="display: flex; justify-content: space-between; margin-top: 10px; flex-wrap: wrap-reverse">
        <div>
            <?= Html::checkbox('check-intervalo', false, ['id' => 'check-intervalo']) ?>
            <span>Considerar Intervalo</span>
        </div>
        <?= Html::button('Aro', ['id' => 'btn-aro', 'class' => 'button', 'style' => 'width: 85px']) ?>
    </div>
<div style="margin-top: 10px;">
    <label for="local" style="margin-right: 5px;"> Local:</label>
    <select name="Local" id="local">
        <option value="Leiria">Leiria</option>
        <option value="Grande">Marinha Grande</option>
    </select>
</div>


    <div class="footer-buttons" style="gap: 10px">
        <?= Html::button('Troca Vol.', ['id' => 'btn-trocar', 'class' => 'button']) ?>
        <?= Html::button('Ver Vol.', ['id' => 'btn-ver', 'class' => 'button']) ?>
        <?= Html::button('Imprimir', ['id' => 'btn-imprimir', 'class' => 'button']) ?>
    </div>
</div>


<?= Dialog::widget(); ?>
<script>
    // LABELS
    const lb_obra = document.querySelector('#lb_obra');
    const lb_volume = document.querySelector('#lb_volume');
    const lb_pecas = document.querySelector('#lb_pecas');
    const lb_aros = document.querySelector('#lb_aros');
    const lb_ultima_referencia = document.querySelector('#lb_ultima_referencia');

    // INPUTS
    const input_volume = document.querySelector("#input-volume");
    const input_referencia = $('#input-referencia');
    const checkbox_palete = document.querySelector('#check-palete');
    const check_intervalo = document.querySelector('#check-intervalo');
    const select = document.getElementById('local');

    // BUTTONS
    const btn_ok = document.querySelector('#btn-ok');
    const btn_trocar = document.querySelector('#btn-trocar');
    const btn_palete = document.querySelector('#btn-palete');
    const btn_aro = document.querySelector('#btn-aro');
    const btn_ver = document.querySelector('#btn-ver');
    const btn_imprimir = document.querySelector('#btn-imprimir');

    // VARIABLES
    let NumObra = 0;
    let volumeX = 0;
    let NumVolume = 0;
    let input_value = 0;
    let local = '';
    let msgErro = '';
    let desIdPai = '';
    let serialNumber = '';
    let qtdTotalPecas = '';
    let desIdPaiAnterior = '';
    let serialNumberAnterior = '';
    let qtdTotalPecasAnterior = '';
    let data = [];
    let ultimo_volume = {
        desIdPaiAnterior: 0,
        input_volume: 0,
        qtdTotalPecasAnterior: 0,
        serialNumberAnterior: 0,
        ultima_referencia: 0,
    };
    let get_ultimo_volume = JSON.parse(window.sessionStorage.getItem('ultimo_volume'));

    // EVENTS
    const enterKeyEvent = new KeyboardEvent('keyup', { key: 'Enter' });

    window.addEventListener("load", (event) => {
        input_referencia.prop("readonly", true);
        input_volume.focus();
        if (get_ultimo_volume){
            input_volume.value = get_ultimo_volume.input_volume;
            input_volume.dispatchEvent(enterKeyEvent);
            lb_ultima_referencia.textContent = get_ultimo_volume.ultima_referencia;
            desIdPaiAnterior = get_ultimo_volume.desIdPaiAnterior;
            serialNumberAnterior = get_ultimo_volume.serialNumberAnterior;
            qtdTotalPecasAnterior = get_ultimo_volume.qtdTotalPecasAnterior;
            ultimo_volume = get_ultimo_volume;
            window.sessionStorage.setItem('ultimo_volume', JSON.stringify(ultimo_volume));
        }
    });



    btn_ver.addEventListener('click', function (event) {
        if (NumVolume === 0) {
            Swal.fire(
                'Atenção!',
                'Introduza um volume',
                'error'
            ).then(function () {
                setTimeout(() => input_volume.focus(), 300);
                input_volume.select();

            });
            return;
        }
        window.location = 'ver-volume?NumObra=' + NumObra + '&NumVolume=' + NumVolume;
    });

    checkbox_palete.addEventListener('click', function (event) {
        event.preventDefault();
    });

    // Mudar o valor da variavel 'local' quando se seleciona um local
    select.addEventListener("change", function () {
        local = select.options[select.selectedIndex].text;
    });

    btn_trocar.addEventListener("click", function () {
        krajeeDialog.confirm("Deseja trocar de volume ?", function (result) {
            if (result) {
                lb_obra.textContent = '';
                lb_volume.textContent = '';
                lb_pecas.textContent = '0';
                NumObra = 0;
                NumVolume = 0;
                setTimeout(() => input_volume.focus(), 200);
                input_volume.select();
            }
        });
    });

    btn_palete.addEventListener("click", function () {
        if (NumVolume === 0) {
            Swal.fire(
                'Atenção!',
                'Introduza um volume',
                'error'
            ).then(function () {
                setTimeout(() => input_volume.focus(), 300);
                input_volume.select();

            });
        } else {
            let string = '';
            if (checkbox_palete.checked) {
                string = 'retirar';
            } else {
                string = 'adicionar';
            }
            krajeeDialog.confirm("Deseja " + string + " palete ?", function (result) {
                if (result) {
                    data = {
                        NumVolumeLido: NumVolume,
                        NumObra: NumObra,
                        Palete: !checkbox_palete.checked,
                    }

                    const setPalete = ajaxGet("../soap/set-palete", data, false);
                    if (setPalete) {
                        data = {
                            NumObra: NumObra,
                            NumVolume: NumVolume,
                        };
                        const palete = ajaxGet("../soap/palete", data, false);
                        if (palete) {
                            checkbox_palete.checked = palete;
                            Swal.fire(
                                'Sucesso!',
                                'Palete adicionada com sucesso',
                                'success'
                            );
                        } else {
                            checkbox_palete.checked = false;
                            Swal.fire(
                                'Sucesso!',
                                'Palete retirada com sucesso',
                                'success'
                            );
                        }

                    } else {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao adicionar a palete',
                            'error'
                        );
                    }
                }
            });
        }

    });

    btn_aro.addEventListener("click", function () {
        if (NumVolume === 0) {
            Swal.fire(
                'Atenção!',
                'Introduza um volume',
                'error'
            ).then(function () {
                setTimeout(() => input_volume.focus(), 300);
                input_volume.select();

            });
        } else {
            krajeeDialog.confirm("Deseja adicionar 1 aro ao volume ?", function (result) {
                if (result) {
                    data = {
                        NumVolumeLido: NumVolume,
                        NumObra: NumObra,
                    }

                    const setAros = ajaxGet("../soap/set-aros", data, false);
                    if (setAros) {
                        data = {
                            NumObra: NumObra,
                            NumVolume: NumVolume,
                        };
                        const numeroAros = ajaxGet("../soap/numero-aros", data, false);
                        if (numeroAros) {
                            lb_aros.textContent = numeroAros;
                        }

                    } else {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao adicionar a palete',
                            'error'
                        );
                    }


                }
            });
        }

    });

    // Ação do butão imprimir ao clicar
    btn_imprimir.addEventListener("click", function () {
        if (NumObra > 0) {
            krajeeDialog.confirm("Deseja imprimir a etiqueta ?", function (result) {
                if (result) {
                    data = {
                        num_obra: NumObra,
                        numVolume: NumVolume,
                        Local: local,
                    }

                    var imprimirEtiquetaLocal = ajaxGet("../soap/imprimir-etiqueta-local", data, true);
                    if (imprimirEtiquetaLocal) {
                        Swal.fire(
                            'Sucesso!',
                            'A etiqueta foi imprimida com sucesso',
                            'success',
                        );
                    } else {
                        Swal.fire(
                            'Atenção!',
                            'Ocorreu um erro ao tentar imprimir',
                            'error',
                        );
                    }
                }
            });
        }
    });

    // Evento do input do volume ao inserir um valor e pressionar Enter
    input_volume.addEventListener("keyup", (event) => {
        input_value = input_volume.value;
        ultimo_volume.input_volume = input_value;
        if (event.key === "Enter") {
            if (input_value.charAt(0) === '0' && input_value.length === 8) {
                NumObra = parseInt(input_value.substring(0, 5));
                NumVolume = parseInt(input_value.substring(5, 8));
                // console.log('NumObra: ' + NumObra);
                // console.log('NumVolume: ' + NumVolume);

                data = {
                    NumObra: NumObra,
                    NumVolume: NumVolume,
                };

                //VOLUME_EXISTE

                const volumeExiste = ajaxGet("../soap/volume-existe", data, false);
                if (!volumeExiste) {
                    NumObra = 0;
                    NumVolume = 0;
                    Swal.fire(
                        'Atenção!',
                        'O volume não existe',
                        'error'
                    ).then(function () {
                        setTimeout(() => input_volume.focus(), 300);
                        input_volume.select();
                    });
                    return;
                }


                //VOLUME_EXPEDIDO

                const volumeExpedido = ajaxGet("../soap/volume-expedido", data, false);
                if (volumeExpedido) {
                    NumObra = 0;
                    NumVolume = 0;
                    Swal.fire(
                        'Atenção!',
                        'O volume já foi expedido.',
                        'error'
                    ).then(function () {
                        setTimeout(() => input_volume.focus(), 300);
                        input_volume.select();
                    });
                    return;
                }
                lb_obra.textContent = NumObra;
                lb_volume.textContent = NumVolume;
                input_referencia.prop("readonly", false);
                input_referencia.focus();

                // NUMERO DE PECAS

                const nTotalPecas = ajaxGet("../soap/get-numero-total-pecas", data, false);
                if (nTotalPecas) {
                    lb_pecas.textContent = nTotalPecas;
                }

                // NUMERO DE AROS

                const numeroAros = ajaxGet("../soap/numero-aros", data, false);

                if (numeroAros >= 0) {
                    lb_aros.textContent = numeroAros;
                }

                // PALETE

                const palete = ajaxGet("../soap/palete", data, false);
                if (palete) {
                    checkbox_palete.checked = palete;
                } else {
                    checkbox_palete.checked = false;
                }
                window.sessionStorage.setItem('ultimo_volume', JSON.stringify(ultimo_volume));
            } else {
                Swal.fire(
                    'Atenção!',
                    'O valor introduzido não é válido',
                    'warning'
                ).then(function () {
                    setTimeout(() => input_volume.focus(), 300);
                    input_volume.select();
                });
            }
        }
    });

    input_referencia.on("keyup", function () {
        if (event.key === "Enter") {
            if (input_referencia.val() === '') {
                Swal.fire(
                    'Atenção!',
                    'Insira um valor',
                    'warning',
                );
            } else {
                trataReferencia();
            }

        }
    });

    btn_ok.addEventListener('click', function () {
        if (input_referencia.val() === '') {
            Swal.fire(
                'Atenção!',
                'Insira um valor',
                'warning',
            );
        } else {
            trataReferencia();
        }
    });

    // FUNCTIONS
    async function trataReferencia() { // Done
        if (input_referencia.val().charAt(0) === '0' && input_referencia.val().length === 8) {
            let NumObraAux = parseInt(input_referencia.val().substring(0, 5));
            let NumVolumeAux = parseInt(input_referencia.val().substring(5, 8));
            data = {
                NumObra: NumObraAux,
                NumVolume: NumVolumeAux,
            };
            const volumeExiste = ajaxGet("../soap/volume-existe", data, false);
            data = {
                NumObra: NumObra,
                NumVolume: NumVolumeAux,
            };
            const volumeExpedido = ajaxGet("../soap/volume-expedido", data, false);

            if (!volumeExpedido && volumeExiste && NumObra === NumObraAux) {
                krajeeDialog.confirm("Deseja passar o conteúdo do volume " + NumVolumeAux + " para o volume " + NumVolume + " ?", function (result) {
                    if (result) {
                        data = {
                            NumVolumeLido: NumVolumeAux,
                            numVolumeaEncher: NumVolume,
                            NumObra: NumObra,
                        }
                        const updateVolume = ajaxGet("../soap/update-volume", data, false);
                        if (updateVolume) {
                            data = {
                                NumObra: NumObra,
                                NumVolume: NumVolume,
                            };
                            const nTotalPecas = ajaxGet("../soap/get-numero-total-pecas", data, false);
                            if (nTotalPecas) {
                                lb_pecas.textContent = nTotalPecas;
                                input_referencia.val('');
                                input_referencia.focus();
                            }

                        } else {
                            Swal.fire(
                                'Erro',
                                'Ocorreu um erro ao passar de um volume para outro.',
                                'error',
                            );
                        }
                    }
                });
            } else {
                if (volumeExpedido) {
                    Swal.fire(
                        'Atenção!',
                        'O volume já foi expedido.',
                        'error'
                    ).then(function () {
                        setTimeout(() => input_referencia.focus(), 300);
                        input_referencia.select();
                    });
                }
                if (!volumeExiste) {
                    Swal.fire(
                        'Atenção!',
                        'O volume não existe',
                        'error',
                    ).then(function () {
                        setTimeout(() => input_referencia.focus(), 300);
                        input_referencia.select();
                    });
                }
                if (NumObra !== NumObraAux) {
                    Swal.fire(
                        'Atenção!',
                        'O numero da obra não é igual.',
                        'error',
                    ).then(function () {
                        setTimeout(() => input_referencia.focus(), 300);
                        input_referencia.select();
                    });
                }
            }
        } else {

            lb_ultima_referencia.textContent = input_referencia.val();
            ultimo_volume.ultima_referencia = lb_ultima_referencia.textContent;
            window.sessionStorage.setItem('ultimo_volume', JSON.stringify(ultimo_volume));
            data = {
                num_obra: NumObra,
                Utilizador: <?= Yii::$app->user->identity->getId()?>,
                Volume: NumVolume,
                referencia: input_referencia.val(),
                multiplos: false,
            };
            const inserirPecasVolume = ajaxGet("../soap/inserir-pecas-volume", data, false);
            volumeX = inserirPecasVolume.Outvolume;
            desIdPai = inserirPecasVolume.DesIdPai;
            serialNumber = inserirPecasVolume.SerialNumber;
            qtdTotalPecas = inserirPecasVolume.QtdTotal;

            switch (inserirPecasVolume.result) {
                case 0:
                    krajeeDialog.confirm("A referência " + input_referencia.val() + " já está dentro do volume, deseja eliminá-la deste volume?", function (result) {
                        if (result) {
                            data = {
                                num_obra: NumObra,
                                numVolume: NumVolume,
                                Referencia: input_referencia.val(),
                            };
                            const eliminarPecaVolume = ajaxGet("../soap/eliminar-peca-volume", data, false);
                            if (eliminarPecaVolume === 1) {
                                Swal.fire(
                                    'Sucesso!',
                                    'Referencia eliminada do volume com sucesso.',
                                    'success',
                                );
                            }
                        }
                    })
                    break;
                case 1:
                    Swal.fire(
                        'Atenção',
                        'A referência ' + input_referencia.val() + ' já está dentro do volume',
                        'warning',
                    );
                    break;
                case 2:
                    krajeeDialog.confirm("A referência " + input_referencia.val() + " já está dentro do volume " + volumeX + ", deseja eliminá-la e inserir neste volume ?", function (result) {
                        if (result) {
                            data = {
                                num_obra: NumObra,
                                numVolume: NumVolume,
                                Referencia: input_referencia.val(),
                            };
                            const eliminarPecaVolume = ajaxGet("../soap/eliminar-peca-volume", data, false);
                            if (eliminarPecaVolume === 1) {
                                data = {
                                    num_obra: NumObra,
                                    Utilizador: <?= Yii::$app->user->identity->getId()?>,
                                    Volume: NumVolume,
                                    referencia: input_referencia.val(),
                                    multiplos: false,
                                };
                                const inserirPecasVolumeAux = ajaxGet("../soap/inserir-pecas-volume", data, false);
                                volumeX = inserirPecasVolumeAux.Outvolume;
                                desIdPai = inserirPecasVolumeAux.DesIdPai;
                                serialNumber = inserirPecasVolumeAux.SerialNumber;
                                qtdTotalPecas = inserirPecasVolumeAux.QtdTotal;
                                if (inserirPecasVolumeAux.result === 1) {
                                    Swal.fire(
                                        'Sucesso!',
                                        'Referencia eliminada e inserida neste volume com sucesso',
                                        'success',
                                    );
                                }
                            }
                        }
                    })
                    break;
                case 3:
                    Swal.fire(
                        'Atenção!',
                        'O volume ' + NumVolume + ' não existe na obra ' + NumObra,
                        'warning',
                    );
                    break;
                case 4:
                    Swal.fire(
                        'Atenção!',
                        'A referencia ' + input_referencia.val() + ' não faz parte desta obra',
                        'warning',
                    );
                    break;
                case 5:
                    Swal.fire(
                        'Atenção!',
                        'A referencia ' + input_referencia.val() + ' é croqui, não pode ser expedida',
                        'warning',
                    );
                    break;
                case 6:
                    Swal.fire(
                        'Atenção!',
                        'A referencia ' + input_referencia.val() + ' já foi expedida',
                        'warning',
                    );
                    break;
                case 7:
                    Swal.fire(
                        'Atenção!',
                        'O volume ' + NumVolume + ' não pode ter sub-obras diferentes',
                        'warning',
                    );
                    break;
                case 8:
                    Swal.fire(
                        'Atenção!',
                        'O volume ' + NumVolume + ' não pode ter prioridades diferentes',
                        'warning',
                    );
                    break;
                case 9:

                    break;
                case -1:
                    Swal.fire(
                        'Atenção!',
                        'Ocorreu um erro ao adicionar a referência ' + input_referencia.val() + ' ao volume ? ' + NumVolume,
                        'error',
                    );
                    break;
            }

            if (desIdPaiAnterior !== '') {
                if (check_intervalo.checked) {
                    if (desIdPai === desIdPaiAnterior) {
                        const result = await processConfirmationDialog();
                        if (result) {
                            data = {
                                num_obra: NumObra,
                                DesidPai: desIdPai,
                                idInicial: serialNumberAnterior,
                                idFinal: serialNumber,
                            };
                            const lista = ajaxGet("../soap/get-pecas-id-pais-intervalo", data, false);

                            for (const [i, item] of lista.int.entries()) {
                                data = {
                                    num_obra: NumObra,
                                    Utilizador: <?= Yii::$app->user->identity->getId()?>,
                                    Volume: NumVolume,
                                    referencia: item,
                                    multiplos: true,
                                };
                                let x = ajaxGet("../soap/inserir-pecas-volume", data, false);
                                volumeX = x.Outvolume;
                                desIdPai = x.DesIdPai;
                                serialNumber = x.SerialNumber;
                                qtdTotalPecas = x.QtdTotal;
                            }
                        } else {
                            check_intervalo.checked = false;
                        }

                    }
                }
                if (desIdPai === desIdPaiAnterior && qtdTotalPecasAnterior === serialNumber) {
                    krajeeDialog.confirm("Detectada leitura da primeira e da última etiqueta, deseja importar todas ?", function (result) {
                        if (result) {
                            data = {
                                NumObra: NumObra,
                                desidpai: desIdPai,
                            }
                            var getPecasIdPais = ajaxGet("../soap/get-pecas-id-pais", data, false);
                            for (const [i, item] of getPecasIdPais.int.entries()) {
                                data = {
                                    num_obra: NumObra,
                                    Utilizador: <?= Yii::$app->user->identity->getId()?>,
                                    Volume: NumVolume,
                                    referencia: item,
                                    multiplos: true,
                                };
                                let x = ajaxGet("../soap/inserir-pecas-volume", data, false);
                                volumeX = x.Outvolume;
                                desIdPai = x.DesIdPai;
                                serialNumber = x.SerialNumber;
                                qtdTotalPecas = x.QtdTotal;
                            }
                        } else {
                            check_intervalo.checked = false;
                        }

                    });

                    des_id_pai_anterior = '';
                    serialNumberAnterior = '';
                    qtdTotalPecasPaiAnterior = '';
                }
            }
            desIdPaiAnterior = desIdPai;
            serialNumberAnterior = serialNumber;
            qtdTotalPecasAnterior = qtdTotalPecas;

            ultimo_volume.desIdPaiAnterior = desIdPaiAnterior;
            ultimo_volume.serialNumberAnterior = serialNumberAnterior;
            ultimo_volume.qtdTotalPecasAnterior = qtdTotalPecasAnterior;

            window.sessionStorage.setItem('ultimo_volume', JSON.stringify(ultimo_volume));
        }

        data = {
            NumObra: NumObra,
            NumVolume: NumVolume,
        }

        const nTotalPecas = ajaxGet("../soap/get-numero-total-pecas", data, false);
        if (nTotalPecas) {
            lb_pecas.textContent = nTotalPecas;
        }
        input_referencia.val('');
        input_referencia.focus();
    }

    async function processConfirmationDialog() {
        return new Promise((resolve, reject) => {
            krajeeDialog.confirm("Considerar etiquetas no intervalo, deseja importar todas ?", function (result) {
                resolve(result);
            });
        });
    }

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