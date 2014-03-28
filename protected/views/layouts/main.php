<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.mobile-1.4.2.min.css" />
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            $.mobile.ajaxEnabled = false;
        });
    </script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mobile-1.4.2.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/application.css" />
</head>
<body class="ui-mobile-viewport ui-overlay-a">
<div data-role="page" class="page" data-quicklinks="true">
    <div data-role="header" class="ui-header header">
        <h1>mp3ler.biz</h1>
    </div>

    <div data-role="navbar" class="lang-select">
        <ul>
            <li><a href="<?=$this->createUrl('language/change', array('language' => 'ru'))?>"><img src="/images/lang_ru.png"></a></li>
            <li><a href="<?=$this->createUrl('language/change', array('language' => 'en'))?>"><img src="/images/lang_en.png"></a></li>
            <li><a href="<?=$this->createUrl('language/change', array('language' => 'az'))?>"><img src="/images/lang_az.png"></a></li>
            <li><a href="<?=$this->createUrl('language/change', array('language' => 'tr'))?>"><img src="/images/lang_tr.png"></a></li>
            <li><a href="<?=$this->createUrl('language/change', array('language' => 'ge'))?>"><img src="/images/lang_ge.png"></a></li>
        </ul>
    </div>

    <div class="ui-header page-header">
        <h1 class="ui-title"><?=Yii::t('app', 'Global search mp3 MP3 Download')?></h1>
    </div>

    <?php $this->widget('application.components.SearchBar', array(
        'query' => $this->search_query,
    )); ?>

    <div role="main" class="ui-content">
        <?php echo $content; ?>

        <ul class="list" data-role="listview" >
            <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Other Services:')?></li>
            <li data-icon="false"><a href="#"><?=Yii::t('app', 'List of all queries')?></a></li>
            <li data-icon="false"><a href="#"><?=Yii::t('app', 'Top downloads for mp3')?></a></li>
        </ul>

        <?php $this->widget('application.components.SocialBar'); ?>
    </div>

    <?php $this->widget('application.components.SearchBar', array(
        'query' => $this->search_query,
    )); ?>

    <div data-role="footer">
        <h1>© mp3ler.biz</h1>
    </div>

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
</body>
</html>