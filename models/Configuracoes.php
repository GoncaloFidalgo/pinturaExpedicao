<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "0_Configuracoes".
 *
 * @property int $Auto_ID
 * @property string|null $Categoria
 * @property string|null $Campo
 * @property string|null $Valor
 * @property string|null $Tarefa
 */
class Configuracoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '0_Configuracoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Categoria', 'Campo'], 'string', 'max' => 100],
            [['Valor'], 'string', 'max' => 200],
            [['Tarefa'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Auto_ID' => 'Auto ID',
            'Categoria' => 'Categoria',
            'Campo' => 'Campo',
            'Valor' => 'Valor',
            'Tarefa' => 'Tarefa',
        ];
    }
}
