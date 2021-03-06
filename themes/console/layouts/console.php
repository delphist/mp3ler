<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo CHtml::encode($this->pageTitle) ?> — Mp3ler</title>

    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/user.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/datepicker3.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php echo $content; ?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-datepicker.js"></script>
<?php if (Yii::app()->language !== 'en') { ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/locales/bootstrap-datepicker.<?=Yii::app()->language?>.js" charset="UTF-8"></script>
<?php } ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/Chart.min.js"></script>

</body>
</html>