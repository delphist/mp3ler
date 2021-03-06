<?php
$this->headerTitle = Yii::t('app', 'Global search mp3 MP3 Download');
$this->pageTitle = Yii::t('app', 'Global search mp3-bedava dinle indir,download,скачать');
?>
<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Top downloads for mp3')?></li>
    <?php foreach($tracks as $track) { ?>
        <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($track->searchTitle)))?>" title="<?=addslashes($track->searchTitle)?> mp3"><?=$track->fullTitle?></a></li>
    <?php } ?>
</ul>

<?php if($pages->pageCount > 1) { ?>
    <?php $this->widget('application.components.Pager', array(
        'pages' => $pages,
        'prevPageLabel' => Yii::t('app', 'Previous page'),
        'nextPageLabel' => Yii::t('app', 'Next page'),
    )) ?>

    <br />
    <br />
<?php } ?>