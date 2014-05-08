<?=$this->renderPartial('_header')?>

    <div class="row marketing">
        <div class="col-lg-12">
            <h4><?=Yii::t('app', 'Accounts')?></a></h4>
            <?php
            $alive_progress = round((100 / $all_accounts) * $alive_accounts);
            $dead_progress = 100 - $alive_progress;
            ?>
            <p class="text-success"><?=Yii::t('app', 'Alive accounts')?>: <?=$alive_accounts?> из <?=$all_accounts?></p>
            <div class="progress">
                <div class="progress-bar progress-bar-success" style="width: <?=$alive_progress?>%">
                    <span class="sr-only"><?=Yii::t('app', 'Alive accounts')?>: <?=$alive_progress?>%</span>
                </div>
                <div class="progress-bar progress-bar-danger" style="width: <?=$dead_progress?>%">
                    <span class="sr-only"><?=Yii::t('app', 'Dead accounts')?>: <?=$dead_progress?>%</span>
                </div>
            </div>
            <?php
            foreach($errors as $error)
            {
                ?>
                <p>
                <h5><?=Yii::t('app', 'Error')?> <?=$error['code']?> <small>(<?=Yii::t('app', 'Accounts:')?> <?= $error['count']?>)</small></h5>
                <code><?=$error['msg']?></code><br />
                </p>
            <?php
            }
            ?>
        </div>

        <div class="col-lg-12">
            <hr />
            <h4><?=Yii::t('app', 'Accounts list')?></h4>
            <table class="table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Vk id : App id</td>
                    <td><?=Yii::t('app', 'Requests')?></td>
                    <td><?=Yii::t('app', 'Captcha')?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($accounts as $account) { ?>
                    <tr>
                        <td style="width: 50px;"><kbd><?=$account->id?></kbd></td>
                        <td>
                        <span class="text-<?= ! $account->is_alive ? 'danger' : 'success' ?>">
                            <?=$account->vk_id?> : <?=$account->app_id?>
                        </span>
                        </td>
                        <td class="col-lg-1">
                            <?=$account->request_count?>
                        </td>
                        <td class="col-lg-1">
                            <?=$account->captcha_count?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?=$this->renderPartial('_footer')?>