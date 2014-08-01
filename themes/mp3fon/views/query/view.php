<?php
$this->searchQuery = $query->text;
$this->pageTitle = Yii::t('mp3fon', '{text} — Search and download founded fresh mp3 tracks with out paying', array('{text}' => CHtml::encode($query->title)));
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php
            if($track !== NULL)
            {
                ?>
                <article class="panel panel-default single">
                    <div class="panel-heading">
                        <h1 class="h1">Download Track mp3 for free</h1>
                    </div>

                    <div class="panel-body">
                        <h2 class="h2"><?=$track->artist_title?> - <small><?=$track->title?></small></h2>

                        <?php
                        $downloadLink = $this->createTrackDownloadUrl($track);
                        ?>

                        <div class="row buttons">
                            <div class="col-md-1"></div>
                            <div class="col-md-5 col-sm-6">
                                <a class="btn btn-block btn-default" href="<?=$downloadLink?>">
                                    <span class="glyphicon glyphicon-play"></span>
                                    Play
                                </a>
                            </div>
                            <div class="col-md-5 col-sm-6">
                                <a class="btn btn-block btn-default" href="<?=$downloadLink?>">
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                    Download
                                </a>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <?php $this->widget('application.components.SocialBar'); ?>
                    </div>
                </article>
            <?php
            }
            ?>
            <?php if(count($query->results) > 0) { ?>
                <article class="panel panel-default mp3List">
                    <div class="panel-heading">
                        <h2 class="h1">Search results</h2>
                    </div>
                    <div class="panel-body">
                        <ul class="topList">
                            <?php foreach($query->results as $result) { ?>
                                <li>
                                    <a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($result['artist_title'].' - '.$result['title'])))?>" title="<?=CHtml::encode($result['artist_title'].' - '.$result['title'])?> mp3"><?='<b>'.CHtml::encode($result['artist_title']).'</b> — '.CHtml::encode($result['title'])?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </article>
            <?php } elseif($track === NULL) { ?>
                <article class="panel panel-default mp3List">
                    <div class="panel-heading">
                        <h2 class="h1">Search results</h2>
                    </div>
                    <div class="panel-body">
                        Sorry, nothing found
                    </div>
                </article>
            <?php } ?>
        </div>

        <?=$this->renderPartial('/default/_sidebar')?>
    </div>
</div>