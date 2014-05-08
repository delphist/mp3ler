<?php
$this->pageTitle = Yii::t('app', 'Partner :partner', array(':partner' => $partner->sitename));
?>

<?=$this->renderPartial('_header')?>

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="panel panel-default" style="margin-bottom: 0px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=CHtml::encode($partner->sitename)?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless" style="margin-bottom: 0px;">
                        <tr>
                            <td class="col-lg-2"><?=Yii::t('app', 'Login:')?></td>
                            <td><?=$partner->username?></td>
                        </tr>
                        <tr>
                            <td class="col-lg-2"><?=Yii::t('app', 'Sitename:')?></td>
                            <td><a href="<?=$partner->siteurl?>"><?=CHtml::encode($partner->sitename)?></a></td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('app', 'E-mail:')?></td>
                            <td><a href="mailto:<?=$partner->email?>"><?=CHtml::encode($partner->email)?></a></td>
                        </tr>
                        <tr>
                            <td class="col-lg-2"><?=Yii::t('app', 'Payed:')?></td>
                            <td><?=$partner->totalPayed?> $</td>
                        </tr>
                        <tr>
                            <td class="col-lg-2"><?=Yii::t('app', 'Not payed:')?></td>
                            <td><?=$partner->totalUnpayed?> $</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?=$this->renderPartial('//partner/_chart_block', array(
    'periodData' => $this->transitionChartData,
    'partner' => $partner,
))?>

    <div class="row marketing">
        <div class="col-lg-12">
            <hr />
            <h4 class="clearfix">
                <?=Yii::t('app', 'Payouts')?>
                <div class="pull-right">
                    <a href="<?=$this->createUrl('console/payout', array('id' => $partner->id))?>" class="btn btn-default"><?=Yii::t('app', 'New payout')?></a>
                </div>
            </h4>

            <?php if(count($payouts) > 0) { ?>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td class="col-lg-1"><?=Yii::t('app', 'ID')?></td>
                        <td><?=Yii::t('app', 'Time period')?></td>
                        <td><?=Yii::t('app', 'Count')?></td>
                        <td><?=Yii::t('app', 'Amount')?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($payouts as $payout) { ?>
                        <tr>
                            <td><?=$payout->id?></td>
                            <td><?=$payout->startDateTimestamp ? date('d-m-Y H:i:s', $payout->startDateTimestamp) : 'Start'?> — <?=date('d-m-Y H:i:s', $payout->endDateTimestamp)?></a></td>
                            <td><?=$payout->transitions?></td>
                            <td><?=$payout->amount?> $</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <?php $this->widget('CLinkPager', array(
                    'pages' => $pages,
                    'header' => '',
                    'nextPageLabel' => 'Next',
                    'prevPageLabel' => 'Prev',
                    'selectedPageCssClass' => 'active',
                    'hiddenPageCssClass' => 'disabled',
                    'htmlOptions' => array(
                        'class' => 'pagination',
                    )
                )) ?>
            <?php } else { ?>
                <div class="alert alert-info">
                    No payouts found.
                </div>
            <?php } ?>
        </div>
    </div>

<?=$this->renderPartial('_footer')?>