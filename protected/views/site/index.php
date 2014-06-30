<?php
$this->headerTitle = Yii::t('app', 'Global search mp3 MP3 Download');
$this->pageTitle = Yii::t('app', 'Global search mp3-bedava dinle indir,download,скачать');
$this->metaDescription = Yii::t('app', 'Global search mp3 mp3ler.biz - bedava mp3 indir, bedava şarkı , en son muzikler,free mp3 download,mp3 скачать');
$this->metaKeywords = Yii::t('app', 'Bedava MP3 Ara , MP3 Indir ,youtube mp3 , download mp3 , free mp3 , pulsuz mp3 yukle , free music 2013, music lyrics , mp3 yukle');
$this->metaAuthor = Yii::t('app', 'Global search mp3 - mp3ler.biz');
?>
<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Last queries:')?></li>
    <?php foreach($tracks as $track) { ?>
        <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($track->title)))?>" title="<?=CHtml::encode($track->title)?> mp3"><?=$track->title?></a></li>
    <?php } ?>
</ul>