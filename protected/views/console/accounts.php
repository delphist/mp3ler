<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li><a href="<?=$this->createUrl('console/index')?>">Консоль</a></li>
            <li class="active"><a href="<?=$this->createUrl('console/accounts')?>">Аккаунты</a></li>
            <li><a href="/">Вернуться на сайт</a></li>
        </ul>
        <h3 class="text-muted">Mp3ler.biz</h3>
    </div>

    <div class="row marketing">
        <div class="col-lg-12">
            <h4>Список всех аккаунтов</h4>
            <table class="table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Vk id : App id</td>
                    <td>Запросы</td>
                    <td>Каптча</td>
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
</div>