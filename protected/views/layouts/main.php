<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?php echo CHtml::encode($this->metaKeywords); ?>"/>
    <meta name="description" content="<?php echo CHtml::encode($this->metaDescription); ?>"/>
    <meta http-equiv="content-language" content="<?=Yii::app()->language?>"/>
    <meta name="author" content="<?php echo CHtml::encode($this->metaAuthor); ?>"/>
    <meta name="distribution" content="Global"/>
    <meta name="rating" content="General"/>
    <meta name="copyright" content="mp3ler.biz"/>
    <meta name="expires" content="no"/>
    <meta name="googlebot" content="NOODP"/>
    <meta name="robots" content="all"/>
    <meta name="robots" content="follow"/>
    <meta name="robots" content="index"/>
    <title><?php echo CHtml::encode($this->pageTitle) ?></title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/jquery.mobile-1.4.2.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/application.css" />
    <?php foreach(array('en', 'ru', 'az', 'tr', 'ge') as $language) { ?>
        <link rel="alternate" href="http://mp3ler.biz<?=$this->createLanguageUrl($language)?>" hreflang="<?=$language?>" />
    <?php } ?>
    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
</head>
<body class="ui-mobile-viewport ui-overlay-a">
<div data-role="page" class="page" data-quicklinks="true">
    <div class="ui-header header ui-bar-inherit">
        <span class="ui-title"><a href="/"><?=Yii::app()->request->cookies->contains('dilxs') ? Chtml::encode(Yii::app()->request->cookies['dilxs']) : 'mp3ler.biz'?></a></span>
    </div>

    <div data-role="navbar" class="lang-select">
        <ul>
            <li><a href="<?=$this->createLanguageUrl('en')?>"><img src="<?=Yii::app()->request->baseUrl?>/images/lang_en.png"></a></li>
            <li><a href="<?=$this->createLanguageUrl('ru')?>"><img src="<?=Yii::app()->request->baseUrl?>/images/lang_ru.png"></a></li>
            <li><a href="<?=$this->createLanguageUrl('az')?>"><img src="<?=Yii::app()->request->baseUrl?>/images/lang_az.png"></a></li>
            <li><a href="<?=$this->createLanguageUrl('tr')?>"><img src="<?=Yii::app()->request->baseUrl?>/images/lang_tr.png"></a></li>
            <li><a href="<?=$this->createLanguageUrl('ge')?>"><img src="<?=Yii::app()->request->baseUrl?>/images/lang_ge.png"></a></li>
        </ul>
    </div>

    <?php if($this->headerTitle) { ?>
    <div class="ui-header page-header">
        <?php if($this->isH1) { ?>
            <h1 class="ui-title"><?=$this->headerTitle?></h1>
        <?php } else { ?>
            <span class="ui-title span-h1"><?=$this->headerTitle?></span>
        <?php } ?>
    </div>
    <?php } ?>

    <?php $this->widget('application.components.SearchBar', array(
        'query' => $this->searchQuery,
    )); ?>

    <div role="main" class="ui-content">
        <?php echo $content; ?>

        <ul class="list" data-role="listview" >
            <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Other Services:')?></li>
            <li data-icon="false"><a href="<?=$this->createUrl('query/top')?>"><?=Yii::t('app', 'List of all queries')?></a></li>
            <li data-icon="false"><a href="<?=$this->createUrl('track/top')?>"><?=Yii::t('app', 'Top downloads for mp3')?></a></li>
            <li data-icon="false"><a href="<?=$this->createUrl('site/partnerInfo')?>"><?=Yii::t('app', 'Wap MasTer (Service)')?></a></li>
        </ul>

        <?php $this->widget('application.components.SocialBar'); ?>
    </div>

    <?php $this->widget('application.components.SearchBar', array(
        'query' => $this->searchQuery,
    )); ?>

    <?php if($this->beginCache('tagbar', array('duration' => 5))) { ?>
        <?php $this->widget('application.components.TagBar'); ?>
        <?php $this->endCache(); } ?>

    <div data-role="footer">
        <h1>© mp3ler.biz</h1>
    </div>

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mobile-1.4.2.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.jplayer.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            $.mobile.ajaxEnabled = false;
        });
    </script>

    <div class="counters">
        <a href="http://toplog.biz/in.php?uid=1509"><img src="http://toplog.biz/count.php?uid=1509/" title="Top Rating" alt="TopLog.Biz" height="10" width="60" /></a>
        <a href="http://mywap.az/in.php?id=64551"><img src="http://mywap.az/counter.php?id=64551" title="Top Rating" alt="myWAP" height="10" width="60" /></a>
        <a href="http://waplog.net/c.shtml?480007"><img src="http://c.waplog.net/480008.cnt" title="Top Rating" alt="waplog" height="10" width="60" /></a>
        <a href="http://wap.top.wapstart.ru/?s=24690"><img src="http://counter.wapstart.ru/index.php?c=30162;b=1;r=0;s=30162" alt="Каталог сайтов Top.WapStart.ru" title="Каталог сайтов Top.WapStart.ru"  height="10" width="50" /></a>
        <a href="http://top.mail.ru/jump?from=2427192"><img src="//d4.c1.b9.a1.top.mail.ru/counter?id=2427192;t=84" style="border:0;" title="Top Rating" height="15" width="60" alt="Рейтинг@Mail.ru" /></a>&nbsp;
        <!--LiveInternet counter--><script type="text/javascript"><!--
            document.write("<a href='http://www.liveinternet.ru/click' "+
                "target=_blank><img src='//counter.yadro.ru/hit?t26.6;r"+
                escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                    screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                ";"+Math.random()+
                "' alt='' title='LiveInternet: number of visitors for today is"+
                " shown' "+
                "border='0' width='88' height='15'><\/a>")
            //--></script><!--/LiveInternet-->

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter24313723 = new Ya.Metrika({id:24313723,
                            webvisor:true,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true});
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="//mc.yandex.ru/watch/24313723" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    </div>
</div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter24313723 = new Ya.Metrika({id:24313723,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/24313723" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-41202489-7']);
    _gaq.push(['_setDomainName', 'mp3ler.biz']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

</body>
</html>