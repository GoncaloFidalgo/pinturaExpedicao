<?php

namespace app\controllers;

use app\models\Soap;
use SoapClient;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class SoapController extends Controller
{


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionVolumeExpedido($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->VolumeExpedido(['NumObra' => $NumObra, 'NumVolume' => $NumVolume]);
            return \yii\helpers\Json::encode($request->VolumeExpedidoResult);
        }
    }

    public function actionVolumeExiste($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->VolumeExiste(['num_obra' => $NumObra, 'Volume' => $NumVolume]);
            return \yii\helpers\Json::encode($request->VolumeExisteResult);
        }
    }

    public function actionGetListaSerialNumberVolume($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetListaSerialNumbertVolume(['num_obra' => $NumObra, 'Volume' => $NumVolume]);
            return \yii\helpers\Json::encode($request->GetListaSerialNumbertVolumeResult);
        }
    }

    public function actionSetSerialNumber($item, $utilizador, $expedicao, $NumVolume, $boolean)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->SetSerialNumber(['serialNumber' => $item, 'NumeroColaborador' => $utilizador, 'numeroExpedicao' => $expedicao, 'Volume' => $NumVolume, 'isVolume' => $boolean]);
            return \yii\helpers\Json::encode($request->SetSerialNumberResult);
        }
    }

    public function actionSetVolumeExpedido($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->SetVolumeExpedido(['NumObra' => $NumObra, 'NumVolume' => $NumVolume]);
            return \yii\helpers\Json::encode($request->SetVolumeExpedidoResult);
        }
    }

    public function actionSetSerialNumberNewVersion($serialNumber, $utilizador, $expedicao, $Volume, $isVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) { //out Int32 SerialNumber, out Int32 QtdTotal, out String DesIdPai
            $request = $soap->SetSerialNumberNewVersion(['serialNumber' => $serialNumber, 'NumeroColaborador' => $utilizador, 'numeroExpedicao' => $expedicao,
                'Volume' => $Volume, 'isVolume' => $isVolume, 'SerialNumber' => 0, 'QtdTotal' => 0, 'DesIdPai' => '']);
            $response = [
                'result' => $request->SetSerialNumberNewVersionResult,
                'SerialNumber' => $request->SerialNumber,
                'QtdTotal' => $request->QtdTotal,
                'DesIdPai' => $request->DesIdPai,
            ];
            return \yii\helpers\Json::encode($response);
        }
    }

    public function actionGetPeso($numeroExpedicao)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetPeso(['numeroExpedicao' => $numeroExpedicao]);
            return \yii\helpers\Json::encode($request->GetPesoResult);
        }
    }

    public function actionApagarReferenciaExpedicao($numExpedicao, $Referencia)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->ApagarReferenciaExpedicao(['numExpedicao' => $numExpedicao, 'Referencia' => $Referencia]);
            return \yii\helpers\Json::encode($request->ApagarReferenciaExpedicaoResult);
        }
    }

    public function actionGetDados($serialNumber)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetDados(['serialNumber' => $serialNumber]);
            return \yii\helpers\Json::encode($request->GetDadosResult);
        }
    }

    public function actionGetPecasIdPais($NumObra, $desidpai)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetPecasIDPais(['num_obra' => $NumObra, 'DesidPai' => $desidpai]);
            return \yii\helpers\Json::encode($request->GetPecasIDPaisResult);
        }
    }

    public function actionInserirVolumeId($num_obra, $Utilizador, $Local)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->InserirVolumeID(['num_obra' => $num_obra, 'Utilizador' => $Utilizador, 'Local' => $Local]);
            return \yii\helpers\Json::encode($request->InserirVolumeIDResult);
        }
    }

    public function actionImprimirEtiquetaLocal($num_obra, $numVolume, $Local)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->ImprimirEtiquetaLocal(['num_obra' => $num_obra, 'numVolume' => $numVolume, 'Local' => $Local]);
            return \yii\helpers\Json::encode($request->ImprimirEtiquetaLocalResult);
        }
    }

    public function actionGetNumeroTotalPecas($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetTotalNumeroPecas(['num_obra' => $NumObra, 'num_volume' => $NumVolume]);
            return \yii\helpers\Json::encode($request->GetTotalNumeroPecasResult);
        }
    }

    public function actionNumeroAros($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->NumeroAros(['NumObra' => $NumObra, 'NumVolumeLido' => $NumVolume]);
            return \yii\helpers\Json::encode($request->NumeroArosResult);
        }
    }

    public function actionSetAros($NumVolumeLido, $NumObra)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->SetAros(['NumObra' => $NumObra, 'NumVolumeLido' => $NumVolumeLido]);
            return \yii\helpers\Json::encode($request->SetArosResult);
        }
    }

    public function actionPalete($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->hasPalete(['NumObra' => $NumObra, 'NumVolumeLido' => $NumVolume]);
            return \yii\helpers\Json::encode($request->hasPaleteResult);
        }
    }

    public function actionSetPalete($NumVolumeLido, $NumObra, $Palete)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        Yii::debug($Palete);
        if ($Palete == 'false') {
            $Palete = 0;
        }
        if ($soap) {
            $request = $soap->SetPalete(['NumVolumeLido' => $NumVolumeLido, 'NumObra' => $NumObra, 'Palete' => $Palete]);
            return \yii\helpers\Json::encode($request->SetPaleteResult);
        }
    }

    public function actionUpdateVolume($NumVolumeLido, $numVolumeaEncher, $NumObra)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->UpdateVolume(['NumVolumeLido' => $NumVolumeLido, 'numVolumeaEncher' => $numVolumeaEncher, 'NumObra' => $NumObra]);
            return \yii\helpers\Json::encode($request->UpdateVolumeResult);
        }
    }

    public function actionInserirPecasVolume($num_obra, $Utilizador, $Volume, $referencia, $multiplos)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->InserirPecasVolume([
                'num_obra' => $num_obra,
                'Utilizador' => $Utilizador,
                'Volume' => $Volume,
                'referencia' => $referencia,
                'multiplos' => $multiplos,
                'Outvolume' => 0,
                'SerialNumber' => 0,
                'QtdTotal' => 0,
                'DesIdPai' => '',
                'msg' => '',
            ]);

            $response = [
                'result' => $request->InserirPecasVolumeResult,
                'Outvolume' => $request->Outvolume,
                'SerialNumber' => $request->SerialNumber,
                'QtdTotal' => $request->QtdTotal,
                'DesIdPai' => $request->DesIdPai,
                'msg' => '',
            ];
            return \yii\helpers\Json::encode($response);
        }
    }

    public function actionGetPecasIdPaisIntervalo($num_obra, $DesidPai, $idInicial, $idFinal)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetPecasIDPaisIntervalo(['num_obra' => $num_obra, 'DesidPai' => $DesidPai, 'idInicial' => $idInicial, 'idFinal' => $idFinal]);
            return \yii\helpers\Json::encode($request->GetPecasIDPaisIntervaloResult);
        }
    }

    public function actionGetFuncionario($Numero)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetFuncionario(['Numero' => $Numero]);
            return \yii\helpers\Json::encode($request->GetFuncionarioResult);
        }
    }

    public function actionInserirPrimario($NumeroFuncionario, $Lote, $referencia)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->InserirPrimario([
                'NumeroFuncionario' => $NumeroFuncionario,
                'Lote' => $Lote,
                'referencia' => $referencia,
            ]);

            $response = [
                'result' => $request->InserirPrimarioResult,
                'SerialNumber' => $request->SerialNumber,
                'QtdTotal' => $request->QtdTotal,
                'DesIdPai' => $request->DesIdPai,
            ];
            return \yii\helpers\Json::encode($response);
        }
    }

    public function actionInserirIntermedio($NumeroFuncionario, $Lote, $referencia)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->InserirIntermedio([
                'NumeroFuncionario' => $NumeroFuncionario,
                'Lote' => $Lote,
                'referencia' => $referencia,
            ]);
            $response = [
                'result' => $request->InserirIntermedioResult,
                'SerialNumber' => $request->SerialNumber,
                'QtdTotal' => $request->QtdTotal,
                'DesIdPai' => $request->DesIdPai,
            ];
            return \yii\helpers\Json::encode($response);
        }
    }

    public function actionInserirAcabamento($NumeroFuncionario, $Lote, $referencia)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->InserirAcabamento([
                'NumeroFuncionario' => $NumeroFuncionario,
                'Lote' => $Lote,
                'referencia' => $referencia,
            ]);
            $response = [
                'result' => $request->InserirAcabamentoResult,
                'SerialNumber' => $request->SerialNumber,
                'QtdTotal' => $request->QtdTotal,
                'DesIdPai' => $request->DesIdPai,
            ];
            return \yii\helpers\Json::encode($response);
        }
    }

    public function actionGetPecasIdPaisSerail($DesidPai)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetPecasIDPaisSerail([
                'DesidPai' => $DesidPai,
            ]);

            return \yii\helpers\Json::encode($request->GetPecasIDPaisSerailResult);
        }
    }

    public function actionLoteExiste($lote)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->LoteExiste(['lote' => $lote]);
            return \yii\helpers\Json::encode($request->LoteExisteResult);
        }
    }
}
