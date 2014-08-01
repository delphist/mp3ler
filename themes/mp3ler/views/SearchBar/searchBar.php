<div class="ui-content">
    <form method="get" action="/">
        <input type="search" name="<?=Yii::app()->params['query_param']?>" placeholder="<?=Yii::t('app', 'Enter song or singer name')?>" value="<?=CHtml::encode($query)?>" />
    </form>
</div>