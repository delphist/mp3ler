<?php
$this->pageTitle = Yii::t('app', 'Transitions');
?>

<?=$this->renderPartial('_header')?>

<?=$this->renderPartial('_chart_block', array(
    'periodData' => $this->transitionChartData,
    'partner' => $partner
))?>

<?=$this->renderPartial('_footer')?>