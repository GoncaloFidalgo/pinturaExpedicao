<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use kartik\icons\Icon;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

Icon::map($this);

$this->title = 'Expedições';
?>
<style>
    body {
        background: linear-gradient(120deg, #4C4C4C, #1e90ff);
        background-size: cover;
        font-family: 'Roboto', sans-serif;
    }

    body:before {
        content: "";
        background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
        opacity: 0.5;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
    }

    .site-login {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        margin: 0 auto;
        max-width: 500px;
        padding: 30px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .site-login h1 {
        color: #333;
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: 2px 2px #1e90ff;
    }

    .site-login input,
    .site-login label {
        color: #333;
        display: block;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        width: 100%;
    }

    .site-login input {
        border: none;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .site-login input:focus {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        outline: none;
    }

    .site-login button {
        background-color: #3CB1EA;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        padding: 10px;
        transition: all 0.3s ease-in-out;
        width: 100%;
    }

    .site-login button:hover {
        background-color: #2493c8;
    }

    .site-login .form-group {
        margin-bottom: 0;
    }

    .site-login .help-block {
        color: #f00;
        margin-top: 5px;
    }

    @media screen and (max-width: 576px) {
        .site-login {
            margin: 30px 10px;
            max-width: none;
            padding: 20px;
        }
    }

</style>

<div class="site-login">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Utilizador') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div>
            <?= Html::submitButton('Login', ['name' => 'login-button', 'id' => 'cmdLogin']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

