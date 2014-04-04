<?php

class QueryController extends Controller
{
    public function actionView($text)
    {
        $query = Query::model()->findByAttributes(array(
            'text' => $text,
        ));

        if( ! $query)
        {
            $query = new Query;
            $query->text = $text;
        }

        $api = new VkApi();
        $query->results = $api->execute('audio.search', array(
            'q' => $query->text,
            'auto_complete' => 1,
            'sort' => 2,
            'count' => 20
        ));

        $query->results_count = 0;
        if(isset($query->results->count) && $query->results->count > 0)
        {
            $query->results_count = $query->results->count;
        }

        $track_url = NULL;
        $track = NULL;
        if($query->results_count)
        {
            foreach($query->results->audio as $result)
            {
                if($result->artist.' - '.$result->title == $query->text)
                {
                    $track = new Track;
                    $track->artist_title = $result->artist;
                    $track->title = $result->title;

                    $track_url = $this->createUrl('/track/download', array(
                        'data' => base64_encode(json_encode($result)),
                    ));
                }
            }
        }

        $this->render('view', array(
            'track' => $track,
            'track_url' => $track_url,
            'query' => $query,
        ));
    }
}