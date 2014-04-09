<?php
$this->headerTitle = Yii::t('app', 'Global search mp3 MP3 Download');
$this->pageTitle = Yii::t('app', 'Global search mp3-bedava dinle indir,download,скачать');
?>
<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'List of all queries')?></li>
    <?php foreach($queries as $query) { ?>
        <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $query->text))?>" title="<?=addslashes($query->text)?> mp3"><?=$query->title?></a></li>
    <?php } ?>
</ul>