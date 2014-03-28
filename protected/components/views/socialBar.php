<ul class="social-bar">
    <?php foreach($socials as $social) { ?>
        <li style="width: <?=floor(100 / count($socials))?>%"><a href="<?=$social['link']?>"><img src="/images/<?=$social['icon']?>"></a></li>
    <?php } ?>
</ul>
