<?php
$this->pageTitle = Yii::t('app', 'New payout');
?>

<?=$this->renderPartial('_header')?>

    <div class="row marketing">
        <div class="col-lg-12">
            <h4>
                <?=Yii::t('app', 'New payout')?>
            </h4>

            <?=CHtml::beginForm('', 'post', array('role' => 'form'))?>

            <?php echo CHtml::errorSummary($model); ?>

            <?=CHtml::activeHiddenField($model, 'user_id')?>
            <?=CHtml::activeHiddenField($model, 'endDateTimestamp')?>
            <?=CHtml::activeHiddenField($model, 'startDateTimestamp')?>
            <?=CHtml::activeHiddenField($model, 'user_id')?>

            <table class="table">
                <tr>
                    <td><?=Yii::t('app', 'Sitename:')?></td>
                    <td><a href="<?=$this->createUrl('console/partner', array('id' => $model->user_id))?>"><?=$model->user->sitename?></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Start date:')?></td>
                    <td><?=$model->startDateTimestamp ? date('d-m-Y H:i:s', $model->startDateTimestamp) : '—'?></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'End date:')?></td>
                    <td><?=date('d-m-Y H:i:s', $model->endDateTimestamp)?></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Amount:')?></td>
                    <td><?=$model->amount?> $</td>
                </tr>
            </table>

            <button class="btn btn-primary" type="submit"><?=Yii::t('app', 'Create')?></button>

            <?=CHtml::endForm()?>
        </div>
    </div>

<?=$this->renderPartial('_footer')?>