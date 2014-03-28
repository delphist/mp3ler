<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Last queries:')?></li>
    <?php foreach($dataProvider->getData() as $query) { ?>
        <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $query->text))?>"><?=$query->text?> <span>(<?=$query->results_count?>)</span></a></li>
    <?php } ?>
</ul>