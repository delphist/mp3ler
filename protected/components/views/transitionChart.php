<canvas class="chart chart-today" width="670" height="200"></canvas>
<small class="text-muted pull-right"><?=Yii::t('app', 'Showing period :from — :to', array(':from' => date('d-m-Y H:i:s', $this->periodData['from']), ':to' => date('d-m-Y H:i:s', $this->periodData['to'])))?></small>
<script type="text/javascript">
    <?php
    $max = 0;
    $listAll = array();
    foreach($dataLists as $list)
    {
        $max = max(max($list), $max);
        $listAll = array_merge($listAll, $list);
    }

    $steps = max(min(4, count(array_unique($listAll)) - 1), 1);
    $step = ceil($max / $steps);
    $step = max(round($step, (strlen($step) * -1) + 1), 1);
    ?>
    $(function() {
        new Chart($(".chart-today").get(0).getContext("2d")).Line({
            labels : [<?=implode($labels,',')?>],
            datasets : [
                <?php foreach($dataLists as $listName => $list) { ?>
                {
                    <?php if($listName == 'countable') { ?>
                    fillColor : "rgba(9,168,161,0.5)",
                    strokeColor : "rgba(9,168,161,1)",
                    pointColor : "rgba(9,168,161,1)",
                    pointStrokeColor : "#666",
                    <?php } elseif($listName == 'notcountable') { ?>
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    <?php } ?>
                    scaleShowLabels: false,
                    data : [<?=implode($list,',')?>]
                },
                <?php } ?>
            ]
        }, {
            scaleOverride: true,
            scaleSteps: <?=$steps?>,
            scaleStepWidth: <?=$step?>,
            scaleStartValue: 0,
            pointDot: false,
        });
    });
</script>