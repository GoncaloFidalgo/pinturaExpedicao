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

class ExpedicaoController extends Controller
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
                        'actions' => ['index', 'set-expedicao', 'picagem', 'listagem'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
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


    public function actionSetExpedicao()
    {
        $this->layout = 'expedicao';
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        $lista_expedicoes = [];
        $array = [];
        if ($soap) {

            $data = $soap->GetExpedicaoes()->GetExpedicaoesResult;

            if(!empty(json_decode(json_encode($data), true))){
                $array = json_decode(json_encode($data->Armazem_Expedicoe), true);
                if (!function_exists('array_is_list')) {
                    function array_is_list(array $arr)
                    {
                        if ($arr === []) {
                            return true;
                        }
                        return array_keys($arr) === range(0, count($arr) - 1);
                    }
                }

                if (!array_is_list($array)){
                    $array = [
                      0 => $array
                    ];
                }
                foreach ($array as $item){
                    $lista_expedicoes[] = $item['N_Expedicao'];
                }
            }

            return $this->render('setExpedicao', [
                'lista_expedicoes' => $lista_expedicoes,
                'data' => $array,
            ]);
        }
        Yii::$app->session->setFlash('error', "Erro ao obter dados!");
        return $this->render('setExpedicao', [
            'lista_expedicoes' => $lista_expedicoes,
            'data' => $array,
        ]);
    }

    public function actionPicagem()
    {
        $this->layout = 'expedicao';

        return $this->render('picagem');
    }

    public function actionListagem()
    {
        $this->layout = 'expedicao';
        return $this->render('listagem');
    }



}
