<?php

/** @var yii\web\View $this */

use kartik\dialog\Dialog;
use yii\bootstrap5\Html;
use yii\bootstrap5\Progress;


$this->title = 'Picagem';
$this->registerJsFile('https://code.jquery.com/jquery-3.7.0.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => $this::POS_HEAD]);

$soap = new \app\models\Soap();
?>
<style>
    input

    =
    [

    'button'
    ]
    {
        color: red
    ;
    }
</style>
<div class="site-index">
    <div class="content">
        <ul>
            <li id="label-expedicao"></li>
        </ul>
        <div class="leitura">
            <?= Html::input('number', 'input-codigo-barras', '', ['id' => 'input-codigo-barras']) ?>
            <?= Html::button('Ok', ['id' => 'btn-ok', 'class' => 'button']) ?>
        </div>
        <div class="div-2000">
            <?= Html::input('number', 'input-volume', '', ['id' => 'input-volume']) ?>
            <?= Html::button('Limpar', ['id' => 'btn-limpar', 'class' => 'button']) ?>
            <?= Html::button('2000', ['id' => 'btn-2000', 'class' => 'button']) ?>
        </div>
        <ul>
            <li id="label_ultima_leitura"></li>
            <li id="label_desIdPai"></li>
        </ul>
        <div class="status"></div>
        <div class="progress-bar">
            <div id="progress" class="progress"></div>
        </div>

        <div id="total-kilos">
            <ul>
                <li>Peso: <span id="lbl_peso" style="font-weight: bold"></span></li>
                <li>Pecas: <span id="lbl_pecas" style="font-weight: bold"></span></li>
                <li>Volumes: <span id="lbl_volume" style="font-weight: bold"></span></li>
            </ul>
        </div>

    </div>
    <div style="display: flex; justify-content: space-between">
        <div>
            <input type="button" id="btn-tirar-foto" value="Tirar Foto" onclick="$('#input-fotos').click();"
                   class="button"/>
            <label for="btn-tirar-foto" id="label-fotos"></label>
        </div>


        <button id="btn-upload" class="button" hidden="hidden">Carregar Fotos</button>
    </div>

    <input style="display:none;" type="file" multiple id="input-fotos" accept="image/*">
</div>
<?= Dialog::widget(); ?>

<script>
    // LABELS
    const label_expedicao = document.querySelector('#label-expedicao');
    const label_total_kilos = document.querySelector('#total-kilos');
    const label_desIdPai = document.querySelector('#label_desIdPai');
    const label_ultima_leitura = document.querySelector('#label_ultima_leitura');
    const label_fotos = document.querySelector('#label-fotos');
    const lbl_peso = document.querySelector('#lbl_peso');
    const lbl_pecas = document.querySelector('#lbl_pecas');
    const lbl_volume = document.querySelector('#lbl_volume');

    // INPUT
    const input_codigo_barras = document.querySelector('#input-codigo-barras');
    const input_volume = document.querySelector('#input-volume');
    const input_fotos = document.querySelector('#input-fotos');

    // BUTTONS
    const btn_fotos = document.querySelector('#btn-fotos');
    const btn_2000 = document.querySelector('#btn-2000');
    const btn_limpar = document.querySelector('#btn-limpar');
    const btn_upload = document.querySelector('#btn-upload');
    const btn_ok = document.querySelector('#btn-ok');

    // ELEMENTS
    const progressElement = document.getElementById('progress');
    const status = $('.status');

    // VARIABLES
    let maxValue = 0;
    let ultima_leitura = 0;
    let ultimo_item = 0;
    let des_id_pai_anterior = '';
    let SerialNumber, QtdTotal, DesIdPai, qtdTotalPecasPaiAnterior, serialNumberAnterior;
    let ajaxRequests = [];
    let ultimo_item_expedido = JSON.parse(window.sessionStorage.getItem('ultimo_item_expedido'));
    let selectedFiles = [];

    expedicao = JSON.parse(window.sessionStorage.getItem('expedicao'));

    window.addEventListener("load", (event) => {
        if (expedicao) {
            label_expedicao.textContent = expedicao.N_Expedicao;
            let getpeso = ajaxGet("../soap/get-peso", {numeroExpedicao: expedicao.N_Expedicao}, false);
            let peso = getpeso.split(' Pcs')[0];
            let volumes = getpeso.split(' ')[3];
            let pecas = getpeso.split(' ')[2];
            lbl_peso.textContent = peso.split(':')[1];
            lbl_volume.textContent = volumes.split(':')[1];
            lbl_pecas.textContent = pecas.split(':')[1];

        }
        input_volume.value = '2000';

        if (ultimo_item_expedido) {
            label_desIdPai.textContent = ultimo_item_expedido.des_id_pai_anterior;
            label_ultima_leitura.textContent = ultimo_item_expedido.ultima_leitura;
            des_id_pai_anterior = ultimo_item_expedido.des_id_pai_anterior;
            serialNumberAnterior = ultimo_item_expedido.serialNumberAnterior;
            qtdTotalPecasPaiAnterior = ultimo_item_expedido.qtdTotalPecasPaiAnterior;
        }
    });

    input_fotos.addEventListener('change', (event) => {
        const files = Array.from(event.target.files);
        selectedFiles.push(...files);
        if (selectedFiles.length === 1) {
            label_fotos.textContent = selectedFiles.length + ' Foto';
        } else {
            label_fotos.textContent = selectedFiles.length + ' Fotos';
        }

        btn_upload.hidden = false;
        // Display the names of all selected files
        selectedFiles.forEach((file) => {
            console.log(file.name);
        });
    });

    btn_upload.addEventListener("click", function () {
        krajeeDialog.confirm("Deseja guardar as fotos ?", function (result) {
            if (result) {
                let files = Array.from(selectedFiles);
                maxValue = files.length;
                makeRequests(files);
            }
        });
    });

    async function makeRequests(array) {
        try {
            const results = [];
            for (let i = 0; i < array.length; i++) {
                const item = array[i];
                const result = await new Promise((resolve, reject) => {
                    let formData = new FormData();
                    formData.append('image', item);
                    formData.append('obra', expedicao.N_Obra);
                    formData.append('expedicao', expedicao.N_Expedicao);
                    console.log(i + 1);

                    $.ajax({
                        url: "upload-images",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (response) {
                            resolve(response);
                            updateProgressBar(i + 1);
                        },
                        error: function (xhr) {
                            reject(xhr);
                        }
                    });
                });
                results.push(result);
            }
            if (results.length === array.length) {
                setTimeout(() =>
                        Swal.fire({
                            icon: 'success',
                            title: 'Concluído!',
                            text: 'Imagens guardadas com sucesso.',
                        }).then(function () {
                            progressElement.style.width = `0%`;
                            btn_upload.hidden = true;
                            label_fotos.textContent = '';
                            selectedFiles = [];
                        })
                    , 500);

            }
        } catch (error) {
            console.error(error);
        }
    }


    btn_ok.addEventListener("click", function () {
        validaLeitura();
    });

    input_codigo_barras.addEventListener("keyup", function (event) {
        if (event.key === 'Enter') {
            validaLeitura();
        }
    });

    btn_2000.addEventListener("click", function () {
        input_volume.value = 2000;
    });

    btn_limpar.addEventListener("click", function () {
        input_volume.value = '';
    });

    function validaLeitura() {
        let NumObra = 0;
        let NumVolume = 0;
        let stop = false;
        let pecas = [];
        let data = [];
        var input_value = input_codigo_barras.value;

        if (label_expedicao.value === '') {
            swal.fire(
                'Atenção!',
                'Escolha uma expedição',
                'error',
            ).then(function () {
                window.location.replace("../expedicao/set-expedicao");
            });
            return;
        }
        if (input_codigo_barras.value === '') {
            swal.fire(
                'Atenção!',
                'Introduza um código',
                'error',
            ).then(function () {
                setTimeout(() => input_codigo_barras.focus(), 300);
            });
            return;
        }

        if (input_value.charAt(0) === '0' && input_value.length === 8) {
            NumObra = parseInt(input_value.substring(0, 5));
            NumVolume = parseInt(input_value.substring(5, 8));
            data = {
                NumObra: NumObra,
                NumVolume: NumVolume,
            };
            if (expedicao.N_Obra !== NumObra) {
                Swal.fire(
                    'Atenção!',
                    'O numero da obra nao pertence á expedição',
                    'error'
                );
                return;
            }
            //VOLUME_EXISTE

            var volumeExiste = ajaxGet("../soap/volume-existe", data, false);
            if (!volumeExiste) {
                NumObra = 0;
                NumVolume = 0;
                Swal.fire(
                    'Atenção!',
                    'O Volume não existe',
                    'error'
                );
                return;
            }

            //VOLUME_EXPEDIDO

            var volumeExpedido = ajaxGet("../soap/volume-expedido", data, false);
            if (volumeExpedido) {
                NumObra = 0;
                NumVolume = 0;
                Swal.fire(
                    'Atenção!',
                    'Este volume já foi expedido.',
                    'error'
                );
                return;
            }

            // Get Lista Serial Number Volume

            var listaSerialNumberVolume = ajaxGet("../soap/get-lista-serial-number-volume", data, false);

            if (listaSerialNumberVolume && Object.keys(listaSerialNumberVolume).length !== 0) {
                listaSerialNumberVolume = listaSerialNumberVolume.int;
                if (typeof listaSerialNumberVolume == 'number') {
                    $.ajax({
                        url: "../soap/set-serial-number",
                        type: "get",
                        //async: false,
                        data: {
                            item: listaSerialNumberVolume,
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
                                stop = true;
                            }
                        },
                        error: function (xhr) {
                            //Do Something to handle error
                        }
                    });
                } else {
                    maxValue = listaSerialNumberVolume.length;
                    for (const [i, item] of listaSerialNumberVolume.entries()) {
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
                                        input_codigo_barras.value = '';
                                        progressElement.style.width = `0%`;
                                    });

                                }
                            },
                            error: function (xhr) {
                                //Do Something to handle error
                            }
                        });

                    }, 2000);
            } else {
                Swal.fire(
                    'Atenção!',
                    'Este volume não tem items',
                    'error'
                );
            }

        } else {
            var dados = [];
            var volume = 2000;
            var a = '';
            if (input_volume.value !== '') {
                volume = parseInt(input_volume.value);
            }
            data = {
                serialNumber: input_value,
                utilizador: <?= Yii::$app->user->identity->getId()?>,
                expedicao: expedicao.N_Expedicao,
                Volume: volume,
                isVolume: false,
            }
            var setSerialNumberNewVersion = ajaxGet("../soap/set-serial-number-new-version", data, false);
            if (setSerialNumberNewVersion) {
                a = setSerialNumberNewVersion.result.split('-');
                SerialNumber = setSerialNumberNewVersion.SerialNumber;
                QtdTotal = setSerialNumberNewVersion.QtdTotal;
                DesIdPai = setSerialNumberNewVersion.DesIdPai;

            }

            GETPESO();

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
                                data = {
                                    numExpedicao: expedicao.N_Expedicao,
                                    Referencia: input_value,
                                }
                                var apagarRefereciaExpedicao = ajaxGet("../soap/apagar-referencia-expedicao", data, false);
                                if (apagarRefereciaExpedicao) {
                                    Swal.fire(
                                        'Sucesso',
                                        'Registo eleminado com sucesso',
                                        'success'
                                    ).then(function () {

                                        input_codigo_barras.value = '';
                                        setTimeout(() => $("#input-codigo-barras").focus(), 300);
                                    });
                                    GETPESO();
                                } else {
                                    Swal.fire(
                                        'Erro',
                                        'Ocorreu um problema ao eliminar o registo da expedição.',
                                        'error',
                                    ).then(function () {
                                        input_codigo_barras.value = '';
                                        setTimeout(() => $("#input-codigo-barras").focus(), 300);
                                    });
                                }
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
                    var getDados = ajaxGet("../soap/get-dados", {serialNumber: input_value}, false);
                    if (getDados) {
                        getDados = getDados.split('|');
                        label_desIdPai.textContent = getDados[0];
                        label_ultima_leitura.textContent = input_value + ' - ' + getDados[1];
                        ultima_leitura = label_ultima_leitura.value;
                    }

                    if (des_id_pai_anterior !== '' && DesIdPai === des_id_pai_anterior && qtdTotalPecasPaiAnterior === SerialNumber) {
                        krajeeDialog.confirm("Detectada leitura da primeira e da última etiqueta, deseja importar todas?", function (result) {
                            if (result) {
                                dados = {
                                    NumObra: expedicao.N_Obra,
                                    desidpai: DesIdPai,
                                }
                                var getPecasIdPais = ajaxGet("../soap/get-pecas-id-pais", dados, false);
                                if (getPecasIdPais) {
                                    getPecasIdPais = getPecasIdPais.int;
                                    maxValue = getPecasIdPais.length;
                                    for (const [i, item] of getPecasIdPais.entries()) {
                                        var request = $.ajax({
                                            url: "../soap/set-serial-number",
                                            type: "get",
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
                                                }
                                                else {
                                                    $.ajax({
                                                        url: "../soap/get-dados",
                                                        type: "get",
                                                        data: {
                                                            serialNumber: input_value,
                                                        },
                                                        success: function (response) {
                                                            response = JSON.parse(response).split('|');
                                                            if (response) {
                                                                label_desIdPai.textContent = response[0];
                                                                label_ultima_leitura.textContent = input_value + ' - ' + response[1];
                                                                ultima_leitura = label_ultima_leitura.value;
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
                                                GETPESO();
                                                progressElement.style.width = `0%`;
                                                input_codigo_barras.value = '';
                                                ultima_leitura = label_ultima_leitura.value;
                                                ultimo_item = {
                                                    des_id_pai_anterior: des_id_pai_anterior,
                                                    serialNumberAnterior: serialNumberAnterior,
                                                    qtdTotalPecasPaiAnterior: qtdTotalPecasPaiAnterior,
                                                    ultima_leitura: ultima_leitura,
                                                };
                                                window.sessionStorage.setItem('ultimo_item_expedido', JSON.stringify(ultimo_item));
                                                des_id_pai_anterior = '';
                                                serialNumberAnterior = '';
                                                qtdTotalPecasPaiAnterior = '';
                                            });
                                        }, 2000);
                                }
                            }
                        });
                    }
                    des_id_pai_anterior = DesIdPai;
                    serialNumberAnterior = SerialNumber;
                    qtdTotalPecasPaiAnterior = QtdTotal;
                    ultimo_item = {
                        des_id_pai_anterior: des_id_pai_anterior,
                        serialNumberAnterior: serialNumberAnterior,
                        qtdTotalPecasPaiAnterior: qtdTotalPecasPaiAnterior,
                        ultima_leitura: ultima_leitura,
                    };
                    window.sessionStorage.setItem('ultimo_item_expedido', JSON.stringify(ultimo_item));
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

    function GETPESO() {
        var getPeso = ajaxGet("../soap/get-peso", {numeroExpedicao: expedicao.N_Expedicao}, false);

        if (getPeso) {
            let peso = getPeso.split(' Pcs')[0];
            let volumes = getPeso.split(' ')[3];
            let pecas = getPeso.split(' ')[2];
            lbl_peso.textContent = peso.split(':')[1];
            lbl_volume.textContent = volumes.split(':')[1];
            lbl_pecas.textContent = pecas.split(':')[1];
        }
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
