<?php

namespace app\models;

use Imagine\Image\Box;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;

class Imagem extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $obra;
    public $expedicao;
    public function rules()
    {
        return [
            [['imageFile'],
                'image',
                'skipOnEmpty' => false,
                'extensions' => 'jpg, png, jpeg'],
            //'maxSize' => 1024 * 1024 * 100
        ];
    }

    public function upload()
    {
        Yii::debug($this->imageFile);
        $data = date('d-m-Y');
        $time = date('H-i-s');
        $id_obra = $this->obra;
        $pathInfo = pathinfo($this->imageFile);
        $extension = $pathInfo['extension'];
        $name = $data . '_' . $time . '.' . $extension;
        $path = Configuracoes::find()->where(['Categoria' => 'Pastas servidor'])->andWhere(['campo' => 'Path Obras'])->one();

        switch (true) {
            case $this->between($id_obra, 0, 99):
                $pastaObra = 'OBs 0000-0099';

                break;
            case  $this->between($id_obra, 100, 999):
                $pastaObra = 'OBs 0' . substr(trim($id_obra), 0, (strlen(trim($id_obra)) - 2)) . "00-0" . substr(trim($id_obra), 0, (strlen(trim($id_obra)) - 2)) . "99";

                break;
            case $id_obra > 999:
                $pastaObra = 'OBs ' . substr(trim($id_obra), 0, (strlen(trim($id_obra)) - 2)) . "00-" . substr(trim($id_obra), 0, (strlen(trim($id_obra)) - 2)) . "99";

                break;
        }


        //Exp.1779 19062020
        $pastaFotos = Yii::getAlias($path->Valor . '/'.$pastaObra . '/OB' . $id_obra . '/10. Expedição/Exp. '.$this->expedicao.' - '.$data.'/Fotos/'.$name);
        Yii::debug($pastaFotos);
        FileHelper::createDirectory(dirname($pastaFotos));
        $fullPath = Yii::getAlias($pastaFotos);
       // $this->imageFile->saveAs(Yii::getAlias($pastaFotos));

        if ($this->imageFile->saveAs($fullPath)) {

            $size = round($this->imageFile->size / 1024);
            list($width, $height) = getimagesize($fullPath);

            // Se a imagem for maior que 3000x3000px, reduz o tamanho
            if ($width >= 2500 || $height >= 2500) {
                $this->resize($fullPath, $fullPath, 75);
                $size = round(filesize($fullPath) / 1024);
            }

            // Se o tamanho for maior que 500 bytes, comprime a imagem
            if ($size > 500) {
                $this->compress($fullPath, $fullPath, 75);
            }

            return true;
        } else {
            return false;
        }
    }
    public function compress($source, $destination, $quality)
    {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);
    }

    public function resize($source, $destination, $quality)
    {
        $info = getimagesize($source);
        list($width, $height) = getimagesize($source);
        $newwidth = $width * 0.5;
        $newheight = $height * 0.5;

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);


        $image = imagescale($image, $newwidth, $newheight);
        imagejpeg($image, $destination, $quality);
    }


    /**
     * @param $value - valor para verificar
     * @param $start - valor inicial da verificacao
     * @param $end - valor final da verificacao
     * @return bool
     *
     * Verifica se um valor está entre os dois valores
     */
    public function between($value, $start, $end)
    {
        return $value >= $start && $value <= $end ? true : false;
    }

}

