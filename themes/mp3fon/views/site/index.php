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
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="h1">Top artists</h2>
                </div>
                <div class="panel-body">
                    <ul class="artistList">
                        <?php for($i = 0; $i < 4; $i++): ?>
                            <li class="clearfix">
                                <a href="#">
                                    <img src="<?=Yii::app()->theme->baseUrl?>/images/nostalgia-77.jpg" alt=""/>
                                    <span class="name">Nostalgia 77</span>
                                    <span class="genre">Jazz</span>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="h1">Video</h2>
                </div>
                <div class="panel-body">
                    <div class="videoblock"></div>
                </div>
            </div>
        </div>
    </div>
</div>