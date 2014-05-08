<?php
$this->pageTitle = Yii::t('app', 'Registration');
?>
<div class="container">
    <?=CHtml::beginForm('', 'post', array('class' => 'form-signin', 'role' => 'form'))?>

    <h2 class="form-signin-heading"><?=Yii::t('app', 'Register as partner')?></h2>

    <?php echo CHtml::errorSummary($model); ?>

    <?=CHtml::activeTextField($model, 'sitename', array(
        'class' => 'form-control first',
        'required' => TRUE,
        'autofocus' => TRUE,
        'placeholder' => Yii::t('app', 'Sitename'),
    ))?>

    <?=CHtml::activeTextField($model, 'email', array(
        'class' => 'form-control middle',
        'required' => TRUE,
        'placeholder' => Yii::t('app', 'E-mail'),
    ))?>

    <?=CHtml::activePasswordField($model, 'password', array(
        'class' => 'form-control middle',
        'required' => TRUE,
        'placeholder' => Yii::t('app', 'Password'),
    ))?>

    <?=CHtml::activePasswordField($model, 'verifyPassword', array(
        'class' => 'form-control last',
        'required' => TRUE,
        'placeholder' => Yii::t('app', 'Verify password'),
    ))?>

    <button class="btn btn-lg btn-primary btn-block" type="submit"><?=Yii::t('app', 'Register')?></button>

    <p>
        <a href="<?=$this->createUrl('user/login')?>"><?= Yii::t('app', 'Login form'); ?></a>
        <br />
        <a href="<?=$this->createUrl('user/remind')?>"><?= Yii::t('app', 'Remind password'); ?></a>
    </p>

    <?=CHtml::endForm()?>
</div>