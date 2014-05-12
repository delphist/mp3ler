<?php
$this->pageTitle = Yii::t('app', 'Partners');
?>

<?=$this->renderPartial('_header')?>

<?=$this->renderPartial('//partner/_chart_block', array(
    'periodData' => $this->transitionChartData,
    'chartLists' => array('countable', 'notcountable'),
))?>

    <div class="row marketing">
        <div class="col-lg-12">
            <hr />
            <h4>
                <?=Yii::t('app', 'Partners list')?>
            </h4>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <td class="col-lg-1"><?=Yii::t('app', 'ID')?></td>
                        <td><?=Yii::t('app', 'Domain')?></td>
                        <td class="col-lg-2"><?=Yii::t('app', 'Today')?></td>
                        <td class="col-lg-2"><?=Yii::t('app', 'This month')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($partners as $partner) { ?>
                    <tr>
                        <td><?=$partner->id?></td>
                        <td><a href="<?=$this->createUrl('console/partner', array('id' => $partner->id))?>"><?=CHtml::encode($partner->sitename)?></a></td>
                        <td><?=$partner->clicksCount('today')?></td>
                        <td><?=$partner->clicksCount('month')?></td>
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
        </div>
    </div>

<?=$this->renderPartial('_footer')?>