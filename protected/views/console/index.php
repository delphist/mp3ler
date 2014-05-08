<?=$this->renderPartial('_header')?>

    <div class="row marketing">
        <div class="col-lg-12">
            <h4><?=Yii::t('app', 'Tracks')?></h4>

            <p class="text-success"><?=Yii::t('app', 'Count')?>: <?=$tracks_count?></p>
        </div>

        <div class="col-lg-12">
            <hr />
            <h4><?=Yii::t('app', 'Queries')?></h4>

            <p class="text-success"><?=Yii::t('app', 'Count')?>: <?=$queries_count?></p>
        </div>
    </div>

<?=$this->renderPartial('_footer')?>