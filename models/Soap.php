<?php

namespace app\models;

use SoapClient;
use Yii;

class Soap
{
   // public $url = Yii::$app->params['soapURL'];
    public $url = '';

    function __construct() {
        $this->url = Yii::$app->params['soapURL'];
    }

    public function init()
    {
        $url = $this->url;
    }
    // Verificar se o URL é válido
    public function verifyUrl()
    {
        $headers = @get_headers($this->url);

        if($headers && strpos( $headers[0], '200')) {
            try {
                return new SoapClient($this->url, ['cache_wsdl' => WSDL_CACHE_NONE]);
            } catch (\SoapFault $e) {
            }
        }
        else {
            return false;
        }
    }

}