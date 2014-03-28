<ul class="social-bar">
    <?php foreach($socials as $social) { ?>
        <li style="width: <?=floor(100 / (count($socials) + 1))?>%"><a href="<?=$social['link']?>"><img src="<?=Yii::app()->request->baseUrl?>/images/<?=$social['icon']?>"></a></li>
    <?php } ?>
</ul>
