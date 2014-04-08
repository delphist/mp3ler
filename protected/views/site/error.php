<?php
$this->pageTitle='Error '.$code.' - '.Yii::app()->name;
?>

<ul class="list" data-role="listview">
    <li class="divider" data-role="list-divider">Error <?php echo $code; ?></li>
</ul>

<p>
    <?php echo CHtml::encode($message); ?>
</p>