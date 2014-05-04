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
2. Настройка конфигурационных файлов `protected/config`. В существующие файлы лезть и менять не надо. Нужно самим создать следующие файлы (примеры лежат в `*.example.php`в этой же директории):
    * `cache.php` — кеширование
    * `database.php` — база данных
    * `captcha.php` — доступы к сервису распознавания каптчи
    * `environment.php` — настройки окружения
    * `params.php` — кастомные параметры (пути, домены)
3. Установить crontab от того же пользователя, что и запускается само приложение

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
    * `t:i` (transition info) — хеш (hash) с данными о кликах (всех) партнера, где field — идентификатор клика, member — json-обьект с данными. TODO: ([сделать так][1])


  [1]: http://instagram-engineering.tumblr.com/post/12202313862/storing-hundreds-of-millions-of-simple-key-value-pairs
