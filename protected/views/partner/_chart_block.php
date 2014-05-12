<?php
if( ! isset($options))
{
    $options = array();
}

$chartLists = isset($chartLists) ? $chartLists : FALSE;

if(isset($partner))
{
    $options['partner'] = $partner;
}

switch($periodData['periodName'])
{
    case 'month':
        $periodTitle = Yii::t('app', 'this month');
        break;

    case 'yesterday':
        $periodTitle = Yii::t('app', 'yesterday');
        break;

    case 'today':
        $periodTitle = Yii::t('app', 'today');
        break;

    case 'custom-month':
        $periodTitle = Yii::app()->dateFormatter->format('LLLL yyyy', $periodData['from']);
        break;

    case 'custom-day':
        $periodTitle = Yii::app()->dateFormatter->format('d MMMM yyyy', $periodData['from']);
        break;
}

$periodAllData = $periodData;
unset($periodAllData['period']);
$transitions = Yii::app()->transitionStatistics->show($periodAllData, $options);
$earnings = Yii::app()->transitionStatistics->transitionEarnings($transitions);

$is_custom = in_array($periodData['periodName'], array('custom-month', 'custom-day'));
?>

<div class="row marketing">
    <div class="col-lg-12">
        <h4>
            <?= isset($hr) && $hr ? '<hr />' : '' ?>
            <?=Yii::t('app', 'Transitions for :period', array(':period' => $periodTitle))?>

            <div class="btn-group pull-right">
                <a href="<?=$this->createUrl('', array_merge($_GET, array('period' => 'today')))?>" class="btn btn-default<?=$periodData['periodName'] == 'today' ? ' active' : ''?>"><?=Yii::t('app', 'Today')?></a>
                <a href="<?=$this->createUrl('', array_merge($_GET, array('period' => 'yesterday')))?>" class="btn btn-default<?=$periodData['periodName'] == 'yesterday' ? ' active' : ''?>"><?=Yii::t('app', 'Yesterday')?></a>
                <a href="<?=$this->createUrl('', array_merge($_GET, array('period' => 'month')))?>" class="btn btn-default<?=$periodData['periodName'] == 'month' ? ' active' : ''?>"><?=Yii::t('app', 'This month')?></a>
                <button class="btn btn-default custom-date-popover<?=$is_custom ? ' active' : ''?>" data-title="<?=Yii::t('app', 'Select custom date')?>" data-placement="bottom"><span class="glyphicon glyphicon-calendar"></span> <?=Yii::t('app', 'Custom')?></button>
            </div>

            <div class="custom-date-popover-content hide">
                <div class="btn-group custom-date-popover-buttons" style="width:100%;">
                    <button type="button" style="width:50%;" data-update="<?=date('Y-m-d', $periodData['from'])?>" data-format="yyyy-mm-dd" data-min-view-mode="0" class="custom-datepicker-popover btn btn-default<?=$periodData['periodName'] == 'custom-day' ? ' active' : ''?>"><?=Yii::t('app', 'Day')?></button>
                    <button type="button" style="width:50%;" data-update="<?=date('Y-m', $periodData['from'])?>" data-format="yyyy-mm" data-min-view-mode="1" class="custom-datepicker-popover btn btn-default<?=($periodData['periodName'] == 'custom-month' || !$is_custom) ? ' active' : ''?>"><?=Yii::t('app', 'Month')?></button>
                </div>
                <div class="custom-datepicker text-center"></div>
            </div>
        </h4>

        <? $this->widget('application.components.TransitionChart', array(
            'periodData' => $periodData,
            'options' => $options,
            'chartLists' => $chartLists,
        )) ?>

        <div>
            <?=Yii::t('app', 'Transitions:')?> <b><?=$transitions?></b>
            <br />
            <?=Yii::t('app', 'Earnings:')?> <b><?=$earnings?>$</b>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        initDatepicker($('.custom-date-popover-content'));

        $('.custom-date-popover').popover({
            html : true,
            container: 'body',
            content: function() {
                return $('.custom-date-popover-content').html();
            }
        }).on('shown.bs.popover', function () {
            var tip = $(this).data('bs.popover').tip();

            $('.btn', tip).off('click').on('click', function() {
                $('.btn', tip).removeClass('active');
                $(this).addClass('active');

                initDatepicker(tip);
            });

            initDatepicker(tip);
        });

        function initDatepicker(selector) {
            var current = $('.btn.active', selector);

            $('.custom-datepicker', selector).datepicker('remove').html('').datepicker({
                format: current.data('format'),
                minViewMode: parseInt(current.data('min-view-mode')),
                autoclose: true,
                startDate: new Date('<?=date('D M d Y H:i:s O', 0)?>'),
                endDate: new Date('<?=date('D M d Y H:i:s O')?>'),
                language: '<?=Yii::app()->language?>',
            }).datepicker('update', current.data('update')).off('changeDate').on('changeDate', function(e) {
                var _url = '<?=$this->createUrl('', array_merge($_GET, array('period' => 'customDateFromPicker')))?>';
                window.location.href = _url.replace('customDateFromPicker', e.format());
            });
        }
    });
</script>