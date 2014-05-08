<?php
$this->pageTitle = Yii::t('app', 'Payouts');
?>

<?=$this->renderPartial('_header')?>

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

<?=$this->renderPartial('_footer')?>