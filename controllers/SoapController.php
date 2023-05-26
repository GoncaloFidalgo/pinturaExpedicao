<?php

namespace app\controllers;

use app\models\Soap;
use SoapClient;
use Yii;
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
            Yii::debug($request);
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

}
