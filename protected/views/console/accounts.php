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
            <h4>Ошибки</h4>
            <?php
            foreach($errors as $error)
            {
                ?>
                <p>
                    Код: <?=$error['code']?><br />
                    Ошибка: <?=$error['msg']?><br />
                    Количество аккаунтов: <?=$error['count']?><br />
                </p>
            <?php
            }
            ?>
        </div>
    </div>
</div>