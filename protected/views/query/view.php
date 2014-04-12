<?php
$this->searchQuery = $query->text;
$this->headerTitle = Yii::t('app', '{text} MP3 Download', array('{text}' => $query->title));
$this->pageTitle = Yii::t('app', '{text}-bedava dinle indir,download,скачать', array('{text}' => $query->title));
$this->metaDescription = Yii::t('app', '{text} mp3ler.biz - bedava mp3 indir, bedava şarkı , en son muzikler,free mp3 download,mp3 скачать', array('{text}' => $query->title));
$this->metaKeywords = Yii::t('app', 'Bedava MP3 Ara , MP3 Indir ,youtube mp3 , download mp3 , free mp3 , pulsuz mp3 yukle , free music 2013, music lyrics , mp3 yukle');
$this->metaAuthor = Yii::t('app', '{text} - mp3ler.biz', array('{text}' => $query->title));
?>
<?php
if($track !== NULL)
{
    ?>
    <span style="margin-top: 0px; padding-top: 0px; font-size: 24px;"><b><?=$track->artist_title.'</b> — '.$track->title?></span>

    <br /><br />

    <fieldset class="ui-grid-a">
        <div class="ui-block-a"><a href="<?=$this->createTrackDownloadUrl($track)?>" data-role="button" class="button" data-corners="false"><?=Yii::t('app', 'Listen')?></a></div>
        <div class="ui-block-b"><a href="<?=$this->createTrackDownloadUrl($track)?>" data-role="button" class="button" data-corners="false"><?=Yii::t('app', 'Download')?></a></div>
    </fieldset>

    <br />

    <?php $this->widget('application.components.SocialBar'); ?>

    <br />
    <br />
<?php
}
?>
<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'On request: {text} Found: {found}', array(
            '{text}' => '<b>'.CHtml::encode($query->title).'</b>',
            '{found}' => Yii::t('app', '{n} audio file|{n} audio files', array((int) $query->results->count, '{n}' => '<b>'.CHtml::encode(number_format((int) $query->results->count, 0, '.', ' ')).'</b>'))
        ))?></li>
    <?php if(count($query->results) > 0) { ?>
        <?php foreach($query->results as $result) { ?>
            <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $result['artist_title'].' - '.$result['title']))?>" title="<?=addslashes($result['artist_title'].' - '.$result['title'])?> mp3"><?='<b>'.CHtml::encode($result['artist_title']).'</b> — '.CHtml::encode($result['title'])?></a></li>
        <?php } ?>
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