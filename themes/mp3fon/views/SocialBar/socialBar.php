<div class="share-btns">
    <?php foreach($socials as $id => $social) { ?>
        <a href="<?=$social['link']?>">
            <img src="<?=Yii::app()->theme->baseUrl?>/images/<?=$social['icon']?>" alt="<?=$social['title']?>"/>
        </a>
    <?php } ?>
</div>