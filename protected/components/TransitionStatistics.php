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
     *               - partner: идентификатор партнера
     *               - countable: какие клики показывать (засчитанные/незасчитанные) (ture/false)
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
     * @param $periodName
     * @return array
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
                return $this->parsePeriodName('today');
                break;
        }
    }

    /**
     * Записывает информацию о переходе
     *
     * @param string $ip ip-адрес посетителя
     * @param int $partner_id id партнера
     * @param bool $counted засчитан ли клик
     * @return bool TRUE в случае если запись была сделана,
     *              FALSE в случае если переход неуникален
     */
    public function commit($ip, $partner_id, $counted = TRUE)
    {
        $partner_ips = new ARedisSet('p:t:ip:'.$partner_id);
        $time = time();

        if( ! $partner_ips->contains($ip))
        {
            $partner_ips->add($ip);

            $id = Yii::app()->redis->getClient()->incr('t:ar');
            $transitions = new ARedisHash('t:i');
            $transitions->add($id, json_encode(array(
                'ip' => $ip,
                'pid' => $partner_id,
                'time' => $time,
            )));

            if($counted)
            {
                $partner_transitions_time = new ARedisSortedSet('p:t:t:'.$partner_id);
                $transitions_time = new ARedisSortedSet('t:t');
            }
            else
            {
                $partner_transitions_time = new ARedisSortedSet('p:nct:t:'.$partner_id);
                $transitions_time = new ARedisSortedSet('nct:t');
            }

            foreach(array($partner_transitions_time, $transitions_time) as $_time)
            {
                $_time->add($id, $time);
            }
        }
    }

    public function transitionEarnings($commits)
    {
        return $commits * 0.01;
    }
}