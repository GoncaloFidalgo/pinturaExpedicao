<?php

namespace app\models;

use SoapClient;

class Soap
{
    public $url = 'http://localhost:51821/TwincatWs.asmx?WSDL';

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