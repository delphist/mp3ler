<?php
$this->searchQuery = $query->text;
$this->headerTitle = Yii::t('app', '{text} MP3 Download', array('{text}' => CHtml::encode($query->text)));
$this->pageTitle = Yii::t('app', '{text}-bedava dinle indir,download,скачать', array('{text}' => CHtml::encode($query->text)));
?>
<?php
if($track !== NULL)
{
    ?>
    <h2 style="margin-top: 0px; padding-top: 0px;"><b><?=$track->artist_title.'</b> — '.$track->title?></h2>

    <fieldset class="ui-grid-a">
        <div class="ui-block-a"><a href="<?=$track_url?>" data-role="button" class="button" data-corners="false">Слушать</a></div>
        <div class="ui-block-b"><a href="<?=$track_url?>" data-role="button" class="button" data-corners="false">Загрузить</a></div>
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
            '{text}' => '<b>'.CHtml::encode($query->text).'</b>',
            '{found}' => Yii::t('app', '{n} audio file|{n} audio files', array((int) $query->results_count, '{n}' => '<b>'.CHtml::encode(number_format((int) $query->results_count, 0, '.', ' ')).'</b>'))
        ))?></li>
    <?php if($query->results_count > 0) { ?>
        <?php foreach($query->results->audio as $result) { ?>
            <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $result->artist.' - '.$result->title))?>"><?='<b>'.$result->artist.'</b> — '.$result->title?></a></li>
        <?php } ?>
    <?php } else { ?>
        <!--
        <? print_r($query->results); ?>
        -->
    <?php } ?>
</ul>