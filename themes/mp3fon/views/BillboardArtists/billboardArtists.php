<ul class="artistList">
    <?php foreach($artists as $artist) { ?>
        <li class="clearfix">
            <a href="<?=$this->getController()->createUrl('query/view', array('text' => $artist->title)) ?>">
                <img src="<?=$artist->image?>" alt=""/>
                <span class="name"><?=$artist->title?></span>
            </a>
        </li>
    <?php } ?>
</ul>