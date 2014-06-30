<?php

class BillboardCommand extends CConsoleCommand
{
    const SOURCE_URL = 'http://www.billboard.com/charts/hot-100?page={x}';

    /**
     * Скачивает данные в модель billboard
     */
    public function actionFetch()
    {
        $page = file_get_contents(str_replace('{x}', '0', self::SOURCE_URL));
        preg_match_all('#<span class="chart_position.*?">(\d+)</span>\s*<h1>(.*?)</h1>.*?<a.*?>(.*?)</a>#isu', $page, $results);

        $ids = array();
        foreach($results[2] as $i => $artist)
        {
            $billboard = new Billboard;
            $billboard->title = html_entity_decode($artist.' '.$results[3][$i]);

            $find_billboard = Billboard::model()->findByAttributes(array(
                'title' => $billboard->title,
            ));

            if($find_billboard !== NULL)
            {
                $billboard = $find_billboard;
            }

            $billboard->position = $results[1][$i];
            $billboard->save();

            $ids[] = $billboard->id;
        }

        Yii::app()->db->createCommand('UPDATE billboard SET position = NULL WHERE id NOT IN ('.implode($ids, ', ').')')->execute();
    }
}