<?php if(Yii::app()->user->hasFlash('success')) { ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?=Yii::app()->user->getFlash('success')?>
    </div>
<?php } ?>