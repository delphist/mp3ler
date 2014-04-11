<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li class="active"><a href="<?=$this->createUrl('console/index')?>">Консоль</a></li>
            <li><a href="<?=$this->createUrl('console/accounts')?>">Аккаунты</a></li>
            <li><a href="/">Вернуться на сайт</a></li>
        </ul>
        <h3 class="text-muted">Mp3ler.biz</h3>
    </div>

    <div class="row marketing">
        <div class="col-lg-12">
            <h4><a href="<?=$this->createUrl('console/accounts')?>">Аккаунты</a></h4>
            <?php
            $alive_progress = round((100 / $all_accounts) * $alive_accounts);
            $dead_progress = 100 - $alive_progress;
            ?>
            <p class="text-success">Живые аккаунты: <?=$alive_accounts?> из <?=$all_accounts?></p>
            <div class="progress">
                <div class="progress-bar progress-bar-success" style="width: <?=$alive_progress?>%">
                    <span class="sr-only">Живые аккаунты: <?=$alive_progress?>%</span>
                </div>
                <div class="progress-bar progress-bar-danger" style="width: <?=$dead_progress?>%">
                    <span class="sr-only">Мертвые аккаунты: <?=$dead_progress?>%</span>
                </div>
            </div>
            <?php
            foreach($errors as $error)
            {
                ?>
                <p>
                <h5>Ошибка <?=$error['code']?> <small>(<?=Yii::t('console', '{n} аккаунт|{n} аккаунта|{n} аккаунтов', $error['count'])?>)</small></h5>
                <code><?=$error['msg']?></code><br />
                </p>
            <?php
            }
            ?>
        </div>

        <div class="col-lg-12">
            <hr />
            <h4>Треки</h4>

            <p class="text-success">Количество: <?=$tracks_count?></p>
        </div>

        <div class="col-lg-12">
            <hr />
            <h4>Запросы</h4>

            <p class="text-success">Количество: <?=$queries_count?></p>
        </div>
    </div>
</div>