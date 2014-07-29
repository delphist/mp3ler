<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search and download founded fresh mp3 tracks with out paying</title>

    <!-- Bootstrap -->
    <link href="<?=Yii::app()->theme->baseUrl?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=Yii::app()->theme->baseUrl?>/css/main.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="icon" type="image/png" href="<?=Yii::app()->theme->baseUrl?>/images/m.png">
</head>
<body>

<header>
    <div class="container">
        <div class="inner">
            <div class="logo2">
                <img src="<?=Yii::app()->theme->baseUrl?>/images/mp3fon.png" alt=""/>
            </div>
            <div class="logo">
                <img src="<?=Yii::app()->theme->baseUrl?>/images/m.png" alt=""/>
            </div>
            <div class="search-form">
                <form action="">
                    <input type="text" placeholder="Search Music" class="form-control"/>
                    <button type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

<?php echo $content; ?>


<audio id="player"></audio>
<?php $lorem = explode(
    " ",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tempor dui et metus interdum faucibus. Duis facilisis, leo eu rutrum sollicitudin, nulla ligula ultricies massa, vitae tempor libero lorem ac orci. Fusce pellentesque porttitor tortor, id ornare odio interdum ac. Suspendisse vulputate, felis non faucibus dictum, elit dui interdum eros, sed mollis ipsum urna sit amet massa. Fusce tincidunt tempor risus. Suspendisse hendrerit metus vitae laoreet posuere. Donec luctus ligula a feugiat euismod. Praesent suscipit tristique tincidunt.

    Sed sapien eros, congue nec purus porttitor, adipiscing luctus velit. Proin molestie libero vitae laoreet consequat. Aenean luctus nibh at iaculis volutpat. Praesent eu euismod nunc, at lobortis elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aenean lacus elit, varius nec fermentum nec, lobortis sit amet lectus. Nam tempor vestibulum nisi, a varius leo volutpat eu. Proin sodales vulputate libero eget elementum. Nunc ut libero lorem. Vestibulum sed faucibus enim. Ut vel est sem. Donec fermentum erat nibh, et faucibus lorem rutrum at."
); ?>
<footer class="container">
    <div class="panel panel-default tag-cloud">
        <div class="panel-body">
            <?php $loremCount = count($lorem); ?>
            <?php for ($i = 0; $i < $loremCount / 2; $i += 2): ?>
                <a href="#"><?= $lorem[$i] . " " . $lorem[$i + 1] ?></a>
            <?php endfor; ?>
        </div>

    </div>
</footer>

<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery-1.11.1.min.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/main.js"></script>
</body>
</html>