<canvas class="chart chart-today" width="670" height="200"></canvas>
<small class="text-muted pull-right">Showing period <?=date('d-m-Y H:i:s', $this->periodData['from'])?> — <?=date('d-m-Y H:i:s', $this->periodData['to'])?></small>
<script type="text/javascript">
    <?php
    $max = max($data);
    $steps = max(min(4, count(array_unique($data)) - 1), 1);
    $step = round($max / $steps);
    $step = max(round($step, (strlen($step) * -1) + 1), 1);
    ?>
    $(function() {
        new Chart($(".chart-today").get(0).getContext("2d")).Line({
            labels : [<?=implode($labels,',')?>],
            datasets : [
                {
                    fillColor : "rgba(9,168,161,0.5)",
                    strokeColor : "rgba(9,168,161,1)",
                    pointColor : "rgba(9,168,161,1)",
                    pointStrokeColor : "#666",
                    scaleShowLabels: false,
                    data : [<?=implode($data,',')?>]
                },
            ]
        }, {
            scaleOverride: true,
            scaleSteps: <?=$steps?>,
            scaleStepWidth: <?=$step?>,
            scaleStartValue: 0
        });
    });
</script>