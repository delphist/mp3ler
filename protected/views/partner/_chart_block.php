<?php
$options = array();

if(isset($partner))
{
    $options['partner'] = $partner;
}

switch($periodData['periodName'])
{
    case 'month':
        $periodTitle = 'this month';
        break;

    case 'yesterday':
        $periodTitle = 'yesterday';
        break;

    default:
    case 'today':
        $periodTitle = 'today';
        break;
}

$periodAllData = $periodData;
unset($periodAllData['period']);
$transitions = Yii::app()->transitionStatistics->show($periodAllData, $options);
$earnings = Yii::app()->transitionStatistics->transitionEarnings($transitions);
?>

<div class="row marketing">
    <div class="col-lg-12">
        <h4>
            <?= isset($hr) && $hr ? '<hr />' : '' ?>
            <?=Yii::t('app', 'Transitions for '.$periodTitle)?>

            <div class="btn-group pull-right">
                <a href="<?=$this->createUrl('', array_merge($_GET, array('periodName' => 'today')))?>" class="btn btn-default<?=$periodData['periodName'] == 'today' ? ' active' : ''?>"><?=Yii::t('app', 'Today')?></a>
                <a href="<?=$this->createUrl('', array_merge($_GET, array('periodName' => 'yesterday')))?>" class="btn btn-default<?=$periodData['periodName'] == 'yesterday' ? ' active' : ''?>"><?=Yii::t('app', 'Yesterday')?></a>
                <a href="<?=$this->createUrl('', array_merge($_GET, array('periodName' => 'month')))?>" class="btn btn-default<?=$periodData['periodName'] == 'month' ? ' active' : ''?>"><?=Yii::t('app', 'This month')?></a>
            </div>
        </h4>

        <? $this->widget('application.components.TransitionChart', array(
            'periodData' => $periodData,
            'options' => $options
        )) ?>

        <div>
            <?=Yii::t('app', 'Transitions:')?> <b><?=$transitions?></b>
            <br />
            <?=Yii::t('app', 'Earnings:')?> <b><?=$earnings?>$</b>
        </div>
    </div>
</div>