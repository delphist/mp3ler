<div class="search-form">
    <form method="get" action="/">
        <input type="text" name="<?=Yii::app()->params['query_param']?>" placeholder="<?=Yii::t('mp3fon', 'Search Music')?>" value="<?=CHtml::encode($query)?>" class="form-control"/>
        <button type="submit">
            <span class="glyphicon glyphicon-search"></span>
        </button>
    </form>
</div>