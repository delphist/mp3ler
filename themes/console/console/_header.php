<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li<?=Yii::app()->controller->action->id == 'index' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/index')?>"><?=Yii::t('app', 'Console')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'accounts' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/accounts')?>"><?=Yii::t('app', 'Accounts')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'partners' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/partners')?>"><?=Yii::t('app', 'Partners')?></a></li>
            <li><a href="<?=$this->createUrl('user/logout')?>"><?=Yii::t('app', 'Logout')?></a></li>
            <li><a href="<?=$this->createUrl('site/index', array('lang' => NULL))?>"><?=Yii::t('app', 'Back to the site')?></a></li>
        </ul>
        <h3 class="text-muted"><?=Yii::t('app', 'Console')?></h3>
    </div>

<?=$this->renderPartial('/default/_flashes')?>