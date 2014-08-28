<?php
$this->pageTitle = Yii::t('mp3fon', 'Free Mp3 Download');
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <article class="panel panel-default mp3List">
                <div class="panel-heading">
                    <h2 class="h1">TOP BillBoard 50 Music list</h2>
                </div>
                <div class="panel-body">
                    <ul class="topList">
                        <?php foreach($tracks as $track) { ?>
                            <li>
                                <a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($track->title)))?>" class="btn play-btn">
                                    <span class="glyphicon glyphicon-play"></span>
                                </a>
                                <a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($track->title)))?>" class="btn download-btn">
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                </a>
                                <a href="<?=$this->createUrl('query/view', array('text' => $this->normalizeQuery($track->title)))?>" title="<?=CHtml::encode($track->title)?> mp3"><?=$track->title?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

            </article>
        </div>

        <?=$this->renderPartial('/default/_sidebar')?>
    </div>
</div>