<div data-role="content">
    <?php
    $i = 0;

    foreach($queries as $query)
    {
        $i++;
        ?>
        <a href="<?=$this->getController()->createUrl('query/view', array('text' => $query->text)) ?>"><?=$query->title?></a>
        <?php
        if($i < count($queries))
        {
            ?>
            /
        <?php
        }
    }
    ?>
</div>