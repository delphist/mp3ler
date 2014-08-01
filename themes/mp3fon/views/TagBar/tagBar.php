<div class="panel panel-default tag-cloud">
    <div class="panel-body">
        <?php $loremCount = count($lorem); ?>
        <?php foreach($queries as $query) { ?>
            <a href="<?=$this->getController()->createUrl('query/view', array('text' => $query->text)) ?>" title="<?=$query->title?>"><?=$query->title?></a>
        <?php } ?>
    </div>
</div>