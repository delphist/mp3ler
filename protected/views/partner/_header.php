<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li<?=Yii::app()->controller->action->id == 'transitions' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('partner/transitions')?>"><?=Yii::t('app', 'Transitions')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'payouts' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('partner/payouts')?>"><?=Yii::t('app', 'Payouts')?></a></li>
            <li<?=Yii::app()->controller->action->id == 'settings' ? ' class="active"' : ''?>><a href="<?=$this->createUrl('partner/settings')?>"><?=Yii::t('app', 'Settings')?></a></li>
            <li><a href="/"><?=Yii::t('app', 'Back to the site')?></a></li>
        </ul>
        <h3 class="text-muted">Mp3ler.biz</h3>
    </div>
