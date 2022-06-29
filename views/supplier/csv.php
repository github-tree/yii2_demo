<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'export csv';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please select an export column</p>

    <?php $form = ActiveForm::begin([
        'id' => 'export-form',
        'layout' => 'horizontal',
        'action' => Yii::$app->urlManager->createUrl(['supplier/export']),
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <div class="col-lg-3">
        <input type="checkbox" name="id" checked="true" disabled="disabled" >
        <label>ID</label>
    </div>

    <div class="col-lg-3">
        <input type="checkbox" name="name">
        <label>Name</label>
    </div>

    <div class="col-lg-3">
        <input type="checkbox" name="code">
        <label>Code</label>
    </div>

    <div class="col-lg-3">
        <input type="checkbox" name="t_status">
        <label>T_status</label>
    </div>

    <?= Html::activeHiddenInput($model,'ids') ?>

    <div class="form-group" style="text-align: center;margin-top: 50px;">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('export', ['class' => 'btn btn-primary', 'name' => 'export-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
