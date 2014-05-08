<?php
$this->pageTitle = Yii::t('app', 'Login');
?>
<div class="container">
    <?=CHtml::beginForm('', 'post', array('class' => 'form-signin', 'role' => 'form'))?>
    <h2 class="form-signin-heading"><?=Yii::t('app', 'Enter')?></h2>

    <?php echo CHtml::errorSummary($model); ?>

    <?=CHtml::activeTextField($model, 'username', array(
        'class' => 'form-control first',
        'required' => TRUE,
        'autofocus' => TRUE,
        'placeholder' => Yii::t('app', 'Sitename (e.g site.com)'),
    ))?>

    <?=CHtml::activePasswordField($model, 'password', array(
        'class' => 'form-control last',
        'required' => TRUE,
        'placeholder' => Yii::t('app', 'Password'),
    ))?>

    <button class="btn btn-lg btn-primary btn-block" type="submit"><?=Yii::t('app', 'Login')?></button>

    <p>
        <a href="<?=$this->createUrl('user/register')?>"><?= Yii::t('app', 'Don\'t have an account? Register a new'); ?></a>
        <br />
        <a href="<?=$this->createUrl('user/remind')?>"><?= Yii::t('app', 'Remind password'); ?></a>
    </p>

    <?=CHtml::endForm()?>
</div>