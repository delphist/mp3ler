<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li<?=Yii::app()->controller->action->id == 'index' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/index')?>"><?=Yii::t('app', 'Console')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'accounts' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/accounts')?>"><?=Yii::t('app', 'Accounts')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'partners' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('console/partners')?>"><?=Yii::t('app', 'Partners')?></a></li>
            <li><a href="/"><?=Yii::t('app', 'Back to the site')?></a></li>
        </ul>
        <h3 class="text-muted">Console</h3>
    </div>