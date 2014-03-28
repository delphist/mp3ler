<ul class="list" data-role="listview" itemscope itemtype="http://schema.org/MusicGroup">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'On request: {text} Found: {found}', array(
            '{text}' => '<b>'.CHtml::encode($query->text).'</b>',
            '{found}' => Yii::t('app', '{n} audio file|{n} audio files', array((int) $query->results_count, '{n}' => '<b>'.CHtml::encode((int) $query->results_count).'</b>'))
        ))?></li>
    <?php foreach(array() as $query) { ?>
        <li data-icon="false"><a href="<?=$this->createUrl('query/view', array('text' => $query->text))?>"><?=$query->text?> <span>(<?=$query->results_count?>)</span></a></li>
    <?php } ?>
</ul>