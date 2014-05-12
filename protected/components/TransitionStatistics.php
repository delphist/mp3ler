<?php

/**
 * Компонент доступа к статистике переходов
 */
class TransitionStatistics extends CApplicationComponent
{
    const HOUR = 3600;
    const DAY = 86400;

    /**
     * Показать статистику переходов партнера в
     * определенный промежуток времени
     *
     * @param array $periodData данные промежутка времени для показа
     *               - from: таймстамп начала промежутка
     *               - to: таймстамп конца промежутка
     *               - period: периодичность разбивки данных (константа)
     * @param array $options опциональные настройки
     *               - partner: партнер
     *               - countable: какие клики показывать (засчитанные/незасчитанные) (true/false)
     *               - return: что возвращать (sum — сумма кликов, count — количество кликов)
     * @return array
     */
    public function show($periodData, $options = array())
    {
        $period = isset($periodData['period']) ? $periodData['period'] : NULL;
        $from = $periodData['from'];
        $to = $periodData['to'];
        $partner = isset($options['partner']) ? $options['partner'] : NULL;
        $countable = isset($options['countable']) ? $options['countable'] : TRUE;
        $return = isset($options['return']) ? $options['return'] : 'count';

        if($period !== NULL)
        {
            $from = floor($from / $period) * $period;
            $to = floor($to / $period) * $period;

            $result = array();
            for($i = $from; $i <= $to; $i += $period)
            {
                $result[] = $this->show(array(
                    'from' => $i,
                    'to' => $i + $period
                ), $options);
            }

            return $result;
        }
        else
        {
            if(time() < $from)
            {
                return 0;
            }

            $t_part = $countable ? 't' : 'nct';

            if($partner)
            {
                $key = 'p:'.$t_part.':t:'.$partner->id;
            }
            else
            {
                $key = $t_part.':t';
            }

            $res = (int) Yii::app()->redis->getClient()->zCount($key, $from, $to);

            if($return == 'sum')
            {
                return $this->transitionEarnings($res);
            }
            else
            {
                return $res;
            }
        }
    }

    /**
     * Конвертирует строковое название периода времени в
     * массив с данными таймстампов
     *
     * Доступные форматы:
     *  - today (сегодня)
     *  - yesterday (вчера)
     *  - month (текущий месяц)
     *  - yyyy-mm (показ за определенный месяц; yyyy - год, mm - месяц)
     *  - yyyy-mm-dd (показ за определенный день, yyyy - год, mm - месяц, dd - день)
     *
     * @param $periodName название периода (см. список доступных форматов)
     * @return array|null массив с данными таймстампов либо NULL в случае невозможности разбора
     */
    public function parsePeriodName($periodName)
    {
        switch($periodName)
        {
            case 'month':
                return array(
                    'period' => TransitionStatistics::DAY,
                    'from' => mktime(0, 0, 0, date("n"), 1),
                    'to' => mktime(23, 59, 59, date("n") + 1, 0),
                    'periodName' => 'month'
                );
                break;

            case 'yesterday':
                return array(
                    'period' => TransitionStatistics::HOUR,
                    'from' => mktime(0, 0, 0, date("n"), date('j') - 1),
                    'to' => mktime(23, 59, 59, date("n"), date('j') - 1),
                    'periodName' => 'yesterday'
                );
                break;

            case 'today':
                return array(
                    'period' => TransitionStatistics::HOUR,
                    'from' => mktime(0, 0, 0),
                    'to' => mktime(23, 59, 59),
                    'periodName' => 'today'
                );
                break;

            default:
                if(preg_match('/^(\d{4})\-(\d{2})\-(\d{2})$/', $periodName, $results))
                {
                    $year = $results[1];
                    $month = $results[2];
                    $day = $results[3];

                    return array(
                        'period' => TransitionStatistics::HOUR,
                        'from' => mktime(0, 0, 0, $month, $day, $year),
                        'to' => mktime(23, 59, 59, $month, $day, $year),
                        'periodName' => 'custom-day'
                    );
                }
                elseif(preg_match('/^(\d{4})\-(\d{2})$/', $periodName, $results))
                {
                    $year = $results[1];
                    $month = $results[2];

                    return array(
                        'period' => TransitionStatistics::DAY,
                        'from' => mktime(0, 0, 0, $month, 1, $year),
                        'to' => mktime(23, 59, 59, $month + 1, 0, $year),
                        'periodName' => 'custom-month'
                    );
                }

                return NULL;

                break;
        }
    }

    /**
     * Записывает информацию о переходе
     *
     * @param User|int $partner обьект партнера User либо id партнера
     * @param array $data информация о клике
     */
    public function commit($partner, array $data)
    {
        Yii::beginProfile('commitTransition');

        $time = time();

        if( ! $partner instanceof User)
        {
            $partner = User::model()->findByPk($partner);
        }
        $partner_id = $partner->id;

        $countable = $this->checkCountable($partner, $data);

        $id = Yii::app()->redis->getClient()->incr('t:ar');
        $transitions = new ARedisHash('t:i');

        Yii::beginProfile('addTransitionInfoToRedis');
        $transitions->add($id, json_encode(array(
            'i' => $ip,
            'p' => $partner_id,
            't' => $time,
            'c' => $countable
        )));
        Yii::endProfile('addTransitionToRedis');

        if($countable)
        {
            $partner_ips = new ARedisSet('p:t:ip:'.$partner_id);
            $partner_ips->add($ip);

            $partner_transitions_time = new ARedisSortedSet('p:t:t:'.$partner_id);
            $transitions_time = new ARedisSortedSet('t:t');
        }
        else
        {
            $partner_transitions_time = new ARedisSortedSet('p:nct:t:'.$partner_id);
            $transitions_time = new ARedisSortedSet('nct:t');
        }

        Yii::beginProfile('addTransitionTimestampsToRedis');
        foreach(array($partner_transitions_time, $transitions_time) as $_time)
        {
            $_time->add($id, $time);
        }
        Yii::endProfile('addTransitionTimestampsToRedis');

        Yii::endProfile('commitTransition');
    }

    /**
     * Проверяет, является ли переход засчитанным (countable)
     *
     * @param User|int $partner обьект партнера User либо id партнера
     * @param array $data массив с информацией о переходе
     *                     - ip: ip-адрес посетителя
     *                     - referer: HTTP_REFERER посетителя
     * @return bool надо ли засчитывать переход
     */
    public function checkCountable($partner, array $data)
    {
        Yii::beginProfile('checkTransitionCountable');

        if( ! $partner instanceof User)
        {
            $partner = User::model()->findByPk($partner);
        }

        /**
         * Проверяем на наличие реферера
         */
        if( ! isset($data['referer']) || empty($data['referer']))
        {
            Yii::endProfile('checkTransitionCountable');

            return FALSE;
        }

        /**
         * Сверяем реферер с нашим доменом
         */
        if( ! $this->compareDomains($data['referer'], $partner->sitename))
        {
            Yii::endProfile('checkTransitionCountable');

            return FALSE;
        }

        /**
         * Проверяем, был ли уже совершен клик с этого ip
         */
        $partner_ips = new ARedisSet('p:t:ip:'.$partner->id);
        if($partner_ips->contains($ip))
        {
            Yii::endProfile('checkTransitionCountable');

            return FALSE;
        }

        Yii::endProfile('checkTransitionCountable');

        return TRUE;
    }

    /**
     * Сравнивает два URL на предмет соответствия доменов
     *
     * @param $url1
     * @param $url2
     * @return bool
     */
    public function compareDomains($url1, $url2)
    {
        return mb_strtolower($this->getDomain($url1)) == mb_strtolower($this->getDomain($url2));
    }

    public function getDomain($url)
    {
        if(strpos('http://', mb_strtolower($url) === FALSE))
        {
            return $url;
        }

        return parse_url($url, PHP_URL_HOST);
    }

    public function transitionEarnings($commits)
    {
        return $commits * 0.01;
    }
}