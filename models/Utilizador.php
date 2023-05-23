<?php

namespace app\models;

use app\models\querys\UtilizadorQuery;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "Utilizador".
 *
 * @property int $numero
 * @property string $nome
 * @property string $palavrachave
 * @property string|null $email
 * @property int $Activo
 * @property int $pica_ponto
 * @property string|null $Expire_date
 * @property int|null $Gerar_Nova_Password
 * @property string|null $Empresa
 * @property string|null $DataNascimento
 * @property string|null $BI_Numero
 * @property string|null $BI_Validade
 * @property string|null $SegSocial
 * @property string|null $Contribuinte
 * @property string|null $Categoria
 * @property string|null $FAM
 * @property int|null $EPI
 * @property int|null $DEclMan
 * @property string|null $Morada_1
 * @property string|null $Morada_2
 * @property string|null $Morada_3
 * @property string|null $RegCriado
 * @property string|null $RegAlterado
 * @property string|null $cartao_id
 * @property string|null $Bancada
 * @property string|null $Cacifo
 * @property string|null $Gaveta
 * @property string|null $Observacoes
 * @property string|null $FigerPrint
 * @property string|null $Obs_Vencimento
 * @property string|null $Fingerprint_expire_date
 * @property int|null $refeicao_paga
 * @property int|null $jantar_pago
 * @property string|null $telefone_1
 * @property string|null $telefone_2
 * @property string|null $telefone_3
 * @property string|null $notas
 * @property string|null $nome_do_PC_autorizado
 * @property string|null $Observacoes_ficha
 * @property string|null $PWDMD5
 * @property int|null $ID_EQUIPA
 * @property int|null $N_MECANOGRAFICO
 * @property string|null $DATA_ADMISSAO
 * @property string|null $NOME_COMPLETO
 * @property string|null $TELEMOVEL_EMPRESA
 * @property string|null $TELEFONE_EMPRESA
 * @property string|null $DEPARTAMENTO_EMAIL
 * @property string|null $FOTO_EMAIL
 * @property string|null $SAUDACAO_EMAIL
 * @property int|null $ACESSO_ALARME
 * @property int|null $ACESSO_WEB
 * @property int|null $ACESSO_PORTOES_EXTERNOS
 * @property int|null $ACESSO_PORTAS_INTERNAS
 * @property string|null $TIPO_TEMPO
 * @property int|null $ID_HORARIO
 * @property string|null $ID_USERLEITOR
 * @property int|null $ACESSO_TOTAL
 * @property string|null $DATA_ACESSOINCIO
 * @property string|null $DATA_ACESSOFIM
 * @property string|null $PAIS
 * @property float|null $DIAS_FERIAS
 * @property string|null $EQUIPA
 * @property int|null $DIAS_FERIAS_ANO_ANTERIOR
 * @property float|null $HORAS_FERIAS
 * @property int|null $TELETRABALHO
 * @property string|null $DATA_ATUALIZACAO_FERIAS
 * @property string|null $TIPO
 * @property int|null $ID_EQUIPAPOCO
 * @property int|null $SEGUNDA
 * @property int|null $TERCA
 * @property int|null $QUARTA
 * @property int|null $QUINTA
 * @property int|null $SEXTA
 * @property int|null $SABADO
 * @property int|null $DOMINGO
 * @property string|null $HORASEGUNDA
 * @property string|null $HORATERCA
 * @property string|null $HORAQUARTA
 * @property string|null $HORAQUINTA
 * @property string|null $HORASEXTA
 * @property string|null $HORASABADO
 * @property string|null $HORADOMINGO
 * @property int|null $DIAS_OFICIAIS
 * @property int|null $DIAS_FERIAS_GOZADOS
 * @property string|null $DATA_SAIDA
 * @property int|null $DIAS_FERIAS_ANO_CORRENTE
 * @property string|null $PASSAPORTE
 * @property string|null $PASSPORTE_VALIDADE
 * @property string|null $CARTAO_SAUDE_EUROPEU
 * @property string|null $CARTAO_SAUDE_VALIDADE
 * @property int|null $ACESSO_PORTOES_STATUS
 * @property int|null $ACESSO_PONTOS
 * @property int|null $TEMPOS_EXTERIOR
 * @property int|null $ALTERAR_EQUIPAMENTO
 * @property int|null $ALTERAR_INTERVENCAO
 * @property int|null $ALTERAR_QUESTIONARIO
 *
 */
class Utilizador extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Utilizadores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'nome'], 'required'],
            [['numero', 'Activo', 'pica_ponto', 'Gerar_Nova_Password', 'EPI', 'DEclMan', 'refeicao_paga', 'jantar_pago', 'ID_EQUIPA', 'N_MECANOGRAFICO', 'ACESSO_ALARME', 'ACESSO_WEB', 'ACESSO_PORTOES_EXTERNOS', 'ACESSO_PORTAS_INTERNAS', 'ID_HORARIO', 'ACESSO_TOTAL', 'DIAS_FERIAS_ANO_ANTERIOR', 'TELETRABALHO', 'ID_EQUIPAPOCO', 'SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO', 'DOMINGO', 'DIAS_OFICIAIS', 'DIAS_FERIAS_GOZADOS', 'DIAS_FERIAS_ANO_CORRENTE', 'ACESSO_PORTOES_STATUS', 'ACESSO_PONTOS', 'TEMPOS_EXTERIOR', 'ALTERAR_EQUIPAMENTO', 'ALTERAR_INTERVENCAO', 'ALTERAR_QUESTIONARIO'], 'integer'],
            [['Expire_date', 'DataNascimento', 'BI_Validade', 'FAM', 'Fingerprint_expire_date', 'DATA_ADMISSAO', 'DATA_ACESSOINCIO', 'DATA_ACESSOFIM', 'DATA_ATUALIZACAO_FERIAS', 'HORASEGUNDA', 'HORATERCA', 'HORAQUARTA', 'HORAQUINTA', 'HORASEXTA', 'HORASABADO', 'HORADOMINGO', 'DATA_SAIDA', 'PASSPORTE_VALIDADE', 'CARTAO_SAUDE_VALIDADE'], 'safe'],
            [['FigerPrint'], 'string'],
            [['DIAS_FERIAS', 'HORAS_FERIAS'], 'number'],
            [['nome', 'cartao_id', 'Bancada', 'Cacifo', 'Gaveta', 'nome_do_PC_autorizado', 'TELEMOVEL_EMPRESA', 'TELEFONE_EMPRESA', 'DEPARTAMENTO_EMAIL', 'FOTO_EMAIL', 'EQUIPA', 'TIPO'], 'string', 'max' => 50],
            [['palavrachave', 'BI_Numero', 'SegSocial', 'Contribuinte', 'Categoria', 'telefone_1', 'telefone_2', 'telefone_3', 'PASSAPORTE', 'CARTAO_SAUDE_EUROPEU'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 150],
            [['Empresa', 'RegCriado', 'RegAlterado'], 'string', 'max' => 80],
            [['Morada_1', 'Morada_2', 'Morada_3', 'Observacoes'], 'string', 'max' => 200],
            [['Obs_Vencimento'], 'string', 'max' => 255],
            [['notas', 'Observacoes_ficha'], 'string', 'max' => 250],
            [['PWDMD5'], 'string', 'max' => 32],
            [['NOME_COMPLETO'], 'string', 'max' => 500],
            [['SAUDACAO_EMAIL', 'ID_USERLEITOR'], 'string', 'max' => 100],
            [['TIPO_TEMPO'], 'string', 'max' => 1],
            [['PAIS'], 'string', 'max' => 30],
            [['numero'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'numero' => 'Numero',
            'nome' => 'Nome',
            'palavrachave' => 'Palavrachave',
            'email' => 'Email',
            'Activo' => 'Activo',
            'pica_ponto' => 'Pica Ponto',
            'Expire_date' => 'Expire Date',
            'Gerar_Nova_Password' => 'Gerar Nova Password',
            'Empresa' => 'Empresa',
            'DataNascimento' => 'Data Nascimento',
            'BI_Numero' => 'Bi Numero',
            'BI_Validade' => 'Bi Validade',
            'SegSocial' => 'Seg Social',
            'Contribuinte' => 'Contribuinte',
            'Categoria' => 'Categoria',
            'FAM' => 'Fam',
            'EPI' => 'Epi',
            'DEclMan' => 'D Ecl Man',
            'Morada_1' => 'Morada 1',
            'Morada_2' => 'Morada 2',
            'Morada_3' => 'Morada 3',
            'RegCriado' => 'Reg Criado',
            'RegAlterado' => 'Reg Alterado',
            'cartao_id' => 'Cartao ID',
            'Bancada' => 'Bancada',
            'Cacifo' => 'Cacifo',
            'Gaveta' => 'Gaveta',
            'Observacoes' => 'Observacoes',
            'FigerPrint' => 'Figer Print',
            'Obs_Vencimento' => 'Obs Vencimento',
            'Fingerprint_expire_date' => 'Fingerprint Expire Date',
            'refeicao_paga' => 'Refeicao Paga',
            'jantar_pago' => 'Jantar Pago',
            'telefone_1' => 'Telefone 1',
            'telefone_2' => 'Telefone 2',
            'telefone_3' => 'Telefone 3',
            'notas' => 'Notas',
            'nome_do_PC_autorizado' => 'Nome Do Pc Autorizado',
            'Observacoes_ficha' => 'Observacoes Ficha',
            'PWDMD5' => 'Pwdmd5',
            'ID_EQUIPA' => 'Id Equipa',
            'N_MECANOGRAFICO' => 'N Mecanografico',
            'DATA_ADMISSAO' => 'Data Admissao',
            'NOME_COMPLETO' => 'Nome Completo',
            'TELEMOVEL_EMPRESA' => 'Telemovel Empresa',
            'TELEFONE_EMPRESA' => 'Telefone Empresa',
            'DEPARTAMENTO_EMAIL' => 'Departamento Email',
            'FOTO_EMAIL' => 'Foto Email',
            'SAUDACAO_EMAIL' => 'Saudacao Email',
            'ACESSO_ALARME' => 'Acesso Alarme',
            'ACESSO_WEB' => 'Acesso Web',
            'ACESSO_PORTOES_EXTERNOS' => 'Acesso Portoes Externos',
            'ACESSO_PORTAS_INTERNAS' => 'Acesso Portas Internas',
            'TIPO_TEMPO' => 'Tipo Tempo',
            'ID_HORARIO' => 'Id Horario',
            'ID_USERLEITOR' => 'Id Userleitor',
            'ACESSO_TOTAL' => 'Acesso Total',
            'DATA_ACESSOINCIO' => 'Data Acessoincio',
            'DATA_ACESSOFIM' => 'Data Acessofim',
            'PAIS' => 'Pais',
            'DIAS_FERIAS' => 'Dias Ferias',
            'EQUIPA' => 'Equipa',
            'DIAS_FERIAS_ANO_ANTERIOR' => 'Dias Ferias Ano Anterior',
            'HORAS_FERIAS' => 'Horas Ferias',
            'TELETRABALHO' => 'Teletrabalho',
            'DATA_ATUALIZACAO_FERIAS' => 'Data Atualizacao Ferias',
            'TIPO' => 'Tipo',
            'ID_EQUIPAPOCO' => 'Id Equipapoco',
            'SEGUNDA' => 'Segunda',
            'TERCA' => 'Terca',
            'QUARTA' => 'Quarta',
            'QUINTA' => 'Quinta',
            'SEXTA' => 'Sexta',
            'SABADO' => 'Sabado',
            'DOMINGO' => 'Domingo',
            'HORASEGUNDA' => 'Horasegunda',
            'HORATERCA' => 'Horaterca',
            'HORAQUARTA' => 'Horaquarta',
            'HORAQUINTA' => 'Horaquinta',
            'HORASEXTA' => 'Horasexta',
            'HORASABADO' => 'Horasabado',
            'HORADOMINGO' => 'Horadomingo',
            'DIAS_OFICIAIS' => 'Dias Oficiais',
            'DIAS_FERIAS_GOZADOS' => 'Dias Ferias Gozados',
            'DATA_SAIDA' => 'Data Saida',
            'DIAS_FERIAS_ANO_CORRENTE' => 'Dias Ferias Ano Corrente',
            'PASSAPORTE' => 'Passaporte',
            'PASSPORTE_VALIDADE' => 'Passporte Validade',
            'CARTAO_SAUDE_EUROPEU' => 'Cartao Saude Europeu',
            'CARTAO_SAUDE_VALIDADE' => 'Cartao Saude Validade',
            'ACESSO_PORTOES_STATUS' => 'Acesso Portoes Status',
            'ACESSO_PONTOS' => 'Acesso Pontos',
            'TEMPOS_EXTERIOR' => 'Tempos Exterior',
            'ALTERAR_EQUIPAMENTO' => 'Alterar Equipamento',
            'ALTERAR_INTERVENCAO' => 'Alterar Intervencao',
            'ALTERAR_QUESTIONARIO' => 'Alterar Questionario',
        ];
    }



    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getTempos()
    {
        return $this->hasMany(Tempo::class, ['ID_USER' => 'numero']);
    }

    /**
     * {@inheritdoc}
     * @return Utilizador
     * Devolve um utilizador ativo com o id igual ao recebido
     */
    public static function findIdentity($id) // Funciona
    {

        return static::findOne(['numero' => $id, 'Activo' => true]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['nome' => $username,  'Activo' => true]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password, $hash)
    {
        $md5 = md5($password);

        return Yii::$app->security->compareString(strtolower($hash), $md5);

    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

}
