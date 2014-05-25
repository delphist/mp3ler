Mp3ler
==========

**Mp3ler** — это сервис поиска и скачивания музыки, использующий социальную сеть вконтакте в качестве источника.

Требования
----------
*  PHP 5.4 + cURL + phpredis + memcache
*  MySQL (основное хранилище данных)
*  Redis (дополнительное хранилище для хранения данных кликов партнерки)
*  Memcached (весь кеш)
*  Yii framework 1.1.14

Установка
---------
1. Стандартный git clone репозитория с программным кодом + установка yii в `../framework`
2. Настройка конфигурационных файлов `protected/config`. В существующие файлы (main.php, urls.php, ...) лезть и менять не надо. Нужно самим создать следующие файлы (примеры лежат в `*.example.php`в этой же директории):
    * `cache.php` — кеширование
    * `database.php` — база данных
    * `captcha.php` — доступы к сервису распознавания каптчи antigate.com
    * `environment.php` — настройки окружения
    * `params.php` — кастомные параметры (пути, домены)
    * `servers.php` — настройка работы сайта при использовании нескольких серверов
    * `session.php` — настройки хранения сессий
3. Установить crontab от того же пользователя, что и запускается само приложение
4. Установить секретную cookie `debug_mode_28f` в 1 (либо перейти в `/console/debug`) чтобы включить WebLog профайлер для себя на сайте

Фоновые задания
---------------
Для корректной работы приложение требует запуска некоторого количества фоновых задач
*  `./yiic vk resetAlive` — запускает обнуление статусов ошибок аккаунтов. Если не запускать, то в конце концов аккаунты отключаются по мелких ошибкам и заново их никто не включает. Запускать рекомендуется раз в 30 минут (подойдет на одном сервере).
*  `./yiicd antigate solve` — запускает решалку каптчей. Выполнен как демон, т.е. должен быть запущен в фоне всегда. Можно добавить в crontab `@reboot`. Внимание! Должен быть запущен только на одном сервере, во избежании того, что одна и та же каптча будет отправлена на решение несколько раз.

Пример crontab'а:

    # Каждый час обнуляем проблемные аккаунты
    @hourly cd /var/www/mp3ler/public_html/mp3ler.biz/protected && ./yiic vk resetAlive > /var/www/mp3ler/public_html/mp3ler.biz/protected/runtime/resetAlive.log 2>&1

    # После ребута запускаем демон, следяющий за статусом каптчей
    @reboot cd /var/www/mp3ler/public_html/mp3ler.biz/protected && ./yiicd antigate solve > /var/www/mp3ler/public_html/mp3ler.biz/protected/runtime/solve.log 2>&1


Таблицы MySQL
-------------
*  `query` — поисковые запросы
*  `query_queue` — очередь поисковых запросов
*  `track` — mp3-треки, сохраненные на диске
*  `user` — пользователи
*  `vk_account` — список аккаунтов vk
*  `vk_cache` — кеш ответа api вконтакте
        
Ключи Redis
-----------
* **Общая информация о кликах**
    * `t:t` (transition : time) — сортированный список (sorted set) **засчитанных кликов**, где score — таймстамп клика, member — идентификатор клика
    * `nct:t` (not counted transition : time) — сортированный список (sorted set) **не засчитанных кликов**, где score — таймстамп клика, member — идентификатор клика
* **Информация о кликах для партнера**
    * `p:t:ip:{id}` (partner transition ip)  — множество (set) уникальных ip засчитанных кликов для партнера `{id}` за все время
    * `p:t:t:{id}` (partner : transition : time) — сортированный список (sorted set) **засчитанных кликов** партнера `{id}`, где score — таймстамп клика, member — идентификатор клика
    * `p:nct:t:{id}` (partner : not counted transition : time) — сортированный список (sorted set) **не засчитанных кликов** партнера `{id}`, где score — таймстамп клика, member — идентификатор клика
* **Клики**
    * `t:ar` (transition autoincrement) — числовое поле для автоинкремента id кликов
    * `t:i` (transition info) — хеш (hash) с данными о кликах (всех) партнера, где field — идентификатор клика, member — json-обьект с данными. TODO: [сделать так][1]
* **Сессии**
    * `s:{id}` (session) — строка с данными сессии с идентификатором {id}, хранение происхоит через стандартные средства php (session.save_handler) и redis.so. Ключ живет определенное время (настраивается в конфиге session.php)
* **Скачивания**
    * `d:{id}` (download) — строка с данными (json) о временной ссылке скачивания трека, где {id} — идентификатор ссылки. Ключ живет определенное время (настраивается в params.php)


  [1]: http://instagram-engineering.tumblr.com/post/12202313862/storing-hundreds-of-millions-of-simple-key-value-pairs
