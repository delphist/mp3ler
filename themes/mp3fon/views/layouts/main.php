<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo CHtml::encode($this->pageTitle) ?></title>

    <meta name="keywords" content="<?php echo CHtml::encode($this->metaKeywords); ?>"/>
    <meta name="description" content="<?php echo CHtml::encode($this->metaDescription); ?>"/>
    <meta http-equiv="content-language" content="<?=Yii::app()->language?>"/>
    <meta name="author" content="<?php echo CHtml::encode($this->metaAuthor); ?>"/>

    <!-- Bootstrap -->
    <link href="<?=Yii::app()->theme->baseUrl?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=Yii::app()->theme->baseUrl?>/css/main.css" rel="stylesheet">

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.0.min.js?v=1" type="text/javascript"></script>

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
                <a href="<?=$this->createUrl('site/index')?>"><img src="<?=Yii::app()->theme->baseUrl?>/images/mp3fon.png" alt=""/></a>
            </div>
            <div class="logo">
                <a href="<?=$this->createUrl('site/index')?>"><img src="<?=Yii::app()->theme->baseUrl?>/images/m.png" alt=""/></a>
            </div>
            <?php $this->widget('application.components.SearchBar', array(
                'query' => $this->searchQuery,
            )); ?>
        </div>
    </div>
</header>

<?php echo $content; ?>

<audio id="player"></audio>

<footer class="container">
    <?php if($this->beginCache('tagbar', array('duration' => 5))) { ?>
        <?php $this->widget('application.components.TagBar'); ?>
    <?php $this->endCache(); } ?>
</footer>

<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery-1.11.1.min.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/main.js"></script>

</body>
</html>