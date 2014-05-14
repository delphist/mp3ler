<?php
$this->searchQuery = $query->text;
$this->headerTitle = Yii::t('app', '{text} MP3 Download', array('{text}' => CHtml::encode($query->title)));
$this->pageTitle = Yii::t('app', '{text}-bedava dinle indir,download,скачать', array('{text}' => $query->title));
$this->metaDescription = Yii::t('app', '{text} mp3ler.biz - bedava mp3 indir, bedava şarkı , en son muzikler,free mp3 download,mp3 скачать', array('{text}' => $query->title));
$this->metaKeywords = Yii::t('app', 'Bedava MP3 Ara , MP3 Indir ,youtube mp3 , download mp3 , free mp3 , pulsuz mp3 yukle , free music 2013, music lyrics , mp3 yukle');
$this->metaAuthor = Yii::t('app', '{text} - mp3ler.biz', array('{text}' => $query->title));
?>
<?php
if($track !== NULL)
{
    Yii::app()->clientScript->registerScriptFile('js/jquery.jplayer.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('jplayer', "
            var player = $('.jplayer');
            var button = $('.jplayer-toggle');

            player.jPlayer({
                play: function(event) {
                    button.text('".Yii::t('app', 'Pause')."');
                },
                pause: function(event) {
                    button.text('".Yii::t('app', 'Listen')."');
                },
                ended: function(event) {
                    button.text('".Yii::t('app', 'Listen')."');
                },
                swfPath: '/js',
                supplied: 'mp3',
                wmode: 'window'
            });

            player.jPlayer('setMedia', {
                mp3: button.attr('href')
            });

            button.click(function(e) {
                if (player.data().jPlayer.status.paused == false) {
                    player.jPlayer('pause');
                } else {
                    player.jPlayer('play');
                }
                e.preventDefault();
            });
", CClientScript::POS_READY);
    ?>
    <span style="margin-top: 0px; padding-top: 0px; font-size: 24px;"><b><?=$track->artist_title.'</b> — '.$track->title?></span>

    <br /><br />

    <fieldset class="ui-grid-a">
        <div class="ui-block-a"><a href="<?=$this->createTrackDownloadUrl($track)?>" data-role="button" class="button jplayer-toggle" data-corners="false"><?=Yii::t('app', 'Listen')?></a></div>
        <div class="ui-block-b"><a href="<?=$this->createTrackDownloadUrl($track)?>" data-role="button" class="button" data-corners="false"><?=Yii::t('app', 'Download')?></a></div>
    </fieldset>

    <div class="jplayer"></div>

    <br />

    <div style="text-align: center;">
        <a href="http://www.ringtonematcher.com/go/?sid=WMBZ&artist=<?=rawurlencode($track->artist_title)?>&song=<?=rawurlencode($track->title)?>" target="_blank"><?=Yii::t('app', 'Get Ringtone')?></a>
    </div>

    <br /><br />

    <?php $this->widget('application.components.SocialBar'); ?>

    <br />
    <br />
<?php
}
else
{
    ?>

    <a href="http://www.ringtonematcher.com/go/?sid=WMBZ&search=<?=rawurlencode($query->title)?>" target="_blank"><?=Yii::t('app', 'Get Ringtone')?></a>

    <br /><br />

    <?php
}
?>

<?php if(count($query->results) > 0) { ?>
<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'On request: {text} Found: {found}', array(
            '{text}' => '<b>'.CHtml::encode($query->title).'</b>',
            '{found}' => Yii::t('app', '{n} audio file|{n} audio files', array((int) $query->results->count, '{n}' => '<b>'.CHtml::encode(number_format((int) $query->results->count, 0, '.', ' ')).'</b>'))
        ))?></li>
    <?php if(count($query->results) > 0) { ?>
        <?php foreach($query->results as $result) { ?>
            <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($result['artist_title'].' - '.$result['title'])))?>" title="<?=CHtml::encode($result['artist_title'].' - '.$result['title'])?> mp3"><?='<b>'.CHtml::encode($result['artist_title']).'</b> — '.CHtml::encode($result['title'])?></a></li>
        <?php } ?>
    <?php } ?>
</ul>
<?php } elseif($track === NULL) { ?>
    <ul class="list" data-role="listview">
        <li class="divider" data-role="list-divider"><?=Yii::t('app', 'No results')?></li>
    </ul>

    <p>Sorry, nothing found</p>
    <br />
<?php } ?>

<?php if($pages->pageCount > 1) { ?>
    <?php $this->widget('application.components.Pager', array(
        'pages' => $pages,
        'prevPageLabel' => Yii::t('app', 'Previous page'),
        'nextPageLabel' => Yii::t('app', 'Next page'),
    )) ?>

    <br />
    <br />
<?php } ?>