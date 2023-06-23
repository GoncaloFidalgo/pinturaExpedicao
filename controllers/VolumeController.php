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
use function PHPUnit\Framework\isEmpty;

class VolumeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'criar-volume', 'get-lista', 'encher-volume', 'ver-volume', 'apagar-linha', 'pesquisar-volume'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

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


    public function actionIndex()
    {
        return $this->render('menu');
    }

    public function actionCriarVolume()
    {
        return $this->render('criar-volume');
    }

    public function actionEncherVolume()
    {

        return $this->render('encher-volume');
    }

    public function actionGetLista($num_obra)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetlstVolumes(['num_obra' => $num_obra]);
            if(!empty((array)$request)){
                if (empty((array)$request->GetlstVolumesResult)) {
                    $lista = null;
                    Yii::$app->session->setFlash('error', 'Esta obra ainda não tem volumes!');
                } else {
                    $listOfVolumes = [];
                    if (is_array($request->GetlstVolumesResult->ListaVolume)) {
                        /*foreach ($request->GetlstVolumesResult->ListaVolume as $item) {
                            $volume = [];

                            $volume['NumeroVolume'] = $item->NumeroVolume;

                            $listOfVolumes[] = $volume;
                        }*/
                        $listOfVolumes = $request->GetlstVolumesResult->ListaVolume;
                    } else {

                        // $volume['NumeroVolume'] = $request->GetlstVolumesResult->ListaVolume->NumeroVolume;

                        // $listOfVolumes[] = (array)$request->GetlstVolumesResult->ListaVolume->NumeroVolume;
                        $listOfVolumes[] = $request->GetlstVolumesResult->ListaVolume;
                    }
                    $lista = new ArrayDataProvider([
                        'allModels' => $listOfVolumes,
                        'pagination' => false,
                        'sort' => [
                            'attributes' => ['NumeroVolume'],
                        ],
                    ]);
                }
            }else{
                $lista = null;
                Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
            }


            return $this->render('criar-volume', [
                'lista' => $lista,
                'num_obra' => $num_obra,
            ]);
        }
    }

    public function actionVerVolume($NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->GetlstConteudoVolumes(['num_obra' => $NumObra, 'num_volume' => $NumVolume]);
            if(!empty((array)$request)){
                if (empty((array)$request->GetlstConteudoVolumesResult)) {
                    $lista = null;
                    Yii::$app->session->setFlash('error', 'Não existem volumes!');
                } else {
                    $list = [];
                    if (is_array($request->GetlstConteudoVolumesResult->ListaConteudoVolume)) {
                        $list = $request->GetlstConteudoVolumesResult->ListaConteudoVolume;
                    } else {
                        $list[] = $request->GetlstConteudoVolumesResult->ListaConteudoVolume;
                    }

                    $lista = new ArrayDataProvider([
                        'allModels' => $list,
                        'pagination' => false,
                        'sort' => [
                            'attributes' => ['referencia'],
                        ],
                    ]);
                }
            }else{
                $lista = null;
                Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
            }


            return $this->render('ver-volume', [
                'NumObra' => $NumObra,
                'NumVolume' => $NumVolume,
                'lista' => $lista
            ]);
        }
    }

    public function actionPesquisarVolume($DesIdPai)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($DesIdPai == 0) {
            return $this->render('pesquisar-volume', [
            ]);
        }
        if ($soap) {
            $request = $soap->GetPecaDesIdPai(['Des_IDPAI' => $DesIdPai]);
            if(!empty((array)$request)){
                if (empty((array)$request->GetPecaDesIdPaiResult)) {
                    $lista = null;
                    Yii::$app->session->setFlash('error', 'Não existem volumes!');
                } else {
                    $list = [];
                    if (is_array($request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA)) {
                        $list = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA;
                    } else {

                        /* $linha['AUTO_ID'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->AUTO_ID;
                         $linha['NUMERO_VOLUME'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->NUMERO_VOLUME;
                         $linha['REFERENCIA'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->REFERENCIA;
                         $linha['NUMERO_LISTA'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->NUMERO_LISTA;
                         $linha['SUB_OBRA'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->SUB_OBRA;
                         $linha['AUTO_ID_VOLUME'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->AUTO_ID_VOLUME;
                         $linha['NUM_OBRA'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->NUM_OBRA;
                         $linha['DES_SERIAL_NUMBER'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->DES_SERIAL_NUMBER;
                         $linha['DES_ID_PAI'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->DES_ID_PAI;
                         $linha['DES_PESO'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->DES_PESO;
                         $linha['DATA_HORA'] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA->DATA_HORA;*/

                        //$list[] = (array)$request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA;
                        $list[] = $request->GetPecaDesIdPaiResult->OBRA_VOLUME_LINHA;

                    }
                    $lista = new ArrayDataProvider([
                        'allModels' => $list,
                        'pagination' => false,
                        'sort' => [
                            'attributes' => ['AUTO_ID'],
                        ],
                    ]);
                }

            }else{
                $lista = null;
                Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
            }

            return $this->render('pesquisar-volume', [
                'desIdPai' => $DesIdPai,
                'lista' => $lista
            ]);
        }
    }

    public function actionApagarLinha($referencia, $NumObra, $NumVolume)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        if ($soap) {
            $request = $soap->ApagarLinha(['num_obra' => $NumObra, 'num_volume' => $NumVolume, 'Referencia' => $referencia]);
            return $this->redirect(['ver-volume',
                'NumObra' => $NumObra,
                'NumVolume' => $NumVolume,
            ]);
        }
    }
}
