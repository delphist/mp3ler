<?php
$this->pageTitle = Yii::t('app', 'Error {code}', array('{code}' => $code));
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <article class="panel panel-default mp3List">
                <div class="panel-heading">
                    <h2 class="h1"><?=Yii::t('app', 'Error {code}', array('{code}' => $code))?></h2>
                </div>
                <div class="panel-body">
                    <?=Yii::t('app', 'Sorry, try later.')?>
                </div>

            </article>
        </div>
    </div>
</div>