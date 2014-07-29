<?php
$this->pageTitle = Yii::t('app', 'Settings');
?>

<?=$this->renderPartial('_header')?>

    <div class="row marketing">
        <div class="col-lg-12">

            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'settings-form',
                'method' => 'post',
                'htmlOptions' => array(
                    'class' => 'form-vertical',
                    'role' => 'form'
                )
            )) ?>

            <h4><?=Yii::t('app', 'Your account')?></h4>
            <br />

            <?php echo $form->errorSummary($model); ?>

            <div class="form-group<?=$form->error($model, 'email') ? ' has-error' : ''?>">
                <?php echo $form->label($model, 'email') ?>
                <?php echo $form->emailField($model, 'email', array('class' => 'form-control', 'readonly' => 'readonly')) ?>
            </div>

            <div class="form-group<?=$form->error($model, 'sitename') ? ' has-error' : ''?>">
                <?php echo $form->label($model, 'sitename') ?>
                <?php echo $form->textField($model, 'sitename', array('class' => 'form-control', 'readonly' => 'readonly')) ?>
                <span class="help-block"><?=Yii::t('app', 'Transitions will be counted only from this domain')?></span>
            </div>

            <div class="form-group">
                <label><?=Yii::t('app', 'Link example')?></label>
                <input type="text" readonly="readonly" class="form-control" value="<?=$this->createAbsoluteUrl('site/index', array('lang' => NULL, 'ref' => $model->sitename))?>">
                <span class="help-block"><?=Yii::t('app', 'You can simply add :tail to all links on the site', array(':tail' => '<code>?ref='.rawurlencode($model->sitename).'</code>'))?></span>
            </div>

            <hr />
            <h4><?=Yii::t('app', 'Payout details')?></h4>
            <br />

            <div class="form-group<?=$form->error($model, 'webmoney_details') ? ' has-error has-details' : ''?>">
                <?php echo $form->label($model, 'webmoney_details') ?>
                <?php echo $form->textField($model, 'webmoney_details', array('class' => 'form-control')) ?>
                <?php if($form->error($model, 'webmoney_details')) { ?>
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <?php } ?>
            </div>

            <div class="form-group<?=$form->error($model, 'paypal_details') ? ' has-error has-details' : ''?>">
                <?php echo $form->label($model, 'paypal_details') ?>
                <?php echo $form->textField($model, 'paypal_details', array('class' => 'form-control')) ?>
                <?php if($form->error($model, 'paypal_details')) { ?>
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <?php } ?>
            </div>

            <hr />
            <h4><?=Yii::t('app', 'Passwords')?></h4>
            <br />
            <div class="form-group<?=$form->error($model, 'password') ? ' has-error has-details' : ''?>">
                <?php echo $form->labelEx($model, 'password') ?>
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control')) ?>
                <?php if($form->error($model, 'password')) { ?>
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <?php } ?>
                <span class="help-block"><?=Yii::t('app', 'Leave blank if you dont want to change')?></span>
            </div>

            <div class="form-group<?=$form->error($model, 'currentPassword') ? ' has-error has-feedback' : ''?>">
                <?php echo $form->labelEx($model, 'currentPassword') ?>
                <?php echo $form->passwordField($model, 'currentPassword', array('class' => 'form-control')) ?>
                <?php if($form->error($model, 'currentPassword')) { ?>
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <?php } ?>
            </div>

            <button class="btn btn-primary" type="submit"><?=Yii::t('app', 'Save')?></button>

            <?php $this->endWidget(); ?>
        </div>
    </div>

<?=$this->renderPartial('_footer')?>