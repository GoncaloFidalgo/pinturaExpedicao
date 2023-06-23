<?php

namespace app\controllers;

use app\models\Imagem;
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
use yii\web\UploadedFile;

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
                        'actions' => ['index', 'set-expedicao', 'picagem', 'listagem', 'get-lista', 'madeira', 'upload-images'],
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

            if (!empty(json_decode(json_encode($data), true))) {
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

                if (!array_is_list($array)) {
                    $array = [
                        0 => $array
                    ];
                }
                foreach ($array as $item) {
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

    public function actionListagem($n_expedicao)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        $lista = null;
        if ($soap) {
            $request = $soap->GetLista(['numeroExpedicao' => $n_expedicao]);
            if (!empty((array)$request)) {
                if (empty((array)$request->GetListaResult)) {
                    Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
                } else {
                    $listOfPecas = [];
                    if (is_array($request->GetListaResult->Lista)) {
                        $listOfPecas = $request->GetListaResult->Lista;
                    } else {
                        /*$volume['NumeroLista'] = $request->GetListaResult->Lista->NumeroLista;
                        $volume['Referencia'] = $request->GetListaResult->Lista->Referencia;
                        $volume['Qtd'] = $request->GetListaResult->Lista->Qtd;
                        $listOfPecas[] = $volume;*/
                        $listOfPecas[] = $request->GetListaResult->Lista;
                    }
                    $lista = new ArrayDataProvider([
                        'allModels' => $listOfPecas,
                        'pagination' => false,
                        'sort' => [
                            'attributes' => ['NumeroLista', 'Referencia', 'Qtd'],
                        ],
                    ]);
                }
            } else {
                $lista = null;
                Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
            }

        }

        $this->layout = 'expedicao';
        return $this->render('/expedicao/listagem', [
            'lista' => $lista,
        ]);
    }

    public function actionMadeira($numeroExpedicao)
    {
        $soap = new Soap();
        $soap = $soap->verifyUrl();
        $lista = null;
        if ($soap) {
            $request = $soap->GetListaMadeira(['numeroExpedicao' => $numeroExpedicao]);
            if (!empty((array)$request)) {
                if (empty((array)$request->GetListaMadeiraResult)) {
                    Yii::$app->session->setFlash('error', 'Esta expedição não tem madeira!');
                } else {
                    $listaMadeira = [];
                    if (is_array($request->GetListaMadeiraResult->ListaMadeira)) {
                        $listaMadeira = $request->GetListaMadeiraResult->ListaMadeira;

                    } else {
                        /*$volume['Referencia'] = $request->GetListaMadeiraResult->ListaMadeira->Referencia;
                        $volume['Quantidade'] = $request->GetListaMadeiraResult->ListaMadeira->Quantidade;
                        $listaMadeira[] = $volume;*/
                        $listaMadeira[] = $request->GetListaMadeiraResult->ListaMadeira;
                    }

                    $lista = new ArrayDataProvider([
                        'allModels' => $listaMadeira,
                        'pagination' => false,
                        'sort' => [
                            'attributes' => ['Referencia', 'Quantidade'],
                        ],
                    ]);
                }
            } else {
                $lista = null;
                Yii::$app->session->setFlash('error', 'Esta expedição não tem peças!');
            }

        }

        $this->layout = 'expedicao';
        return $this->render('/expedicao/madeira', [
            'lista' => $lista,
        ]);
    }

    public function actionUploadImages()
    {
        $image = new Imagem();
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $image->obra = $request['obra'];
            $image->expedicao = $request['expedicao'];
            $image->imageFile = UploadedFile::getInstanceByName('image');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($image->upload()) {

                sleep(1);
                return ['success' => true];
            } else {
                return ['error' => true];
            }
        }
    }
}

