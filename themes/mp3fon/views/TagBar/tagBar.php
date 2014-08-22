<div class="panel panel-default tag-cloud">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
            <?php
            $count = count($queries);
            ?>
        <?php foreach($queries as $query) { ?>
            <?php
            $i++;
            if($i % ceil($count / 4) == 0)
            {
                echo '</div><div class="col-md-3">';
            }
            ?>
            <a href="<?=$this->getController()->createUrl('query/view', array('text' => $query->text)) ?>" title="<?=$query->title?>"><?=$query->title?></a><br />
        <?php } ?>
            </div>
        </div>
    </div>
</div>