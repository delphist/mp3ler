<?php

class BillboardArtistCommand extends CConsoleCommand
{
    const SOURCE_URL = 'http://www.billboard.com/charts/artist-100?page={x}';

    /**
     * Скачивает данные в модель BillboardArtist
     */
    public function actionFetch()
    {
        $directory = Yii::app()->params['storage_path'].'/billboard_artist/';

        if( ! is_dir($directory))
        {
            mkdir($directory, 0775);
        }

        $pagenum = 0;
        $ids = array();

        while(count($ids) < 5)
        {
            $page = file_get_contents(str_replace('{x}', $pagenum, self::SOURCE_URL));
            preg_match_all('#<div class="rank".*?>.*?(\d+).*?</div>.*?<img.*?src="(.*?)".*?alt="(.*?)".*?>#isu', $page, $results);

            foreach($results[3] as $i => $artist)
            {
                $billboard = new BillboardArtist;
                $billboard->title = html_entity_decode($artist);

                $find_billboard = BillboardArtist::model()->findByAttributes(array(
                    'title' => $billboard->title,
                ));

                if($find_billboard !== NULL)
                {
                    $billboard = $find_billboard;
                }

                $image_url = $results[2][$i];
                $image_url = str_replace('thumbnail_120', 'thumbnail_170', $image_url);

                $billboard->position = $results[1][$i];
                $billboard->save();

                $image_contents = file_get_contents($image_url);
                $filename = $directory.$billboard->id.'.jpg';
                file_put_contents($filename, $image_contents);
                chmod($filename, 0775);

                $ids[] = $billboard->id;
            }

            $pagenum++;
        }

        Yii::app()->db->createCommand('UPDATE billboard_artist SET position = NULL WHERE id NOT IN ('.implode($ids, ', ').')')->execute();
    }
}