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