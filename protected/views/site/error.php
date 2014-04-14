<?php
$this->pageTitle = Yii::t('app', 'Error {code}', array('{code}' => $code));
?>

<ul class="list" data-role="listview">
    <li class="divider" data-role="list-divider"><?=Yii::t('app', 'Error {code}', array('{code}' => $code))?></li>
</ul>

<p>
    <?=Yii::t('app', 'Sorry, try later.')?>
</p>
<br />