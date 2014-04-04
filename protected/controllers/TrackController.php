<?php

class TrackController extends Controller
{
    public function actionDownload($data)
    {
        try
        {
            $track = json_decode(base64_decode($data));

            $file =  file_get_contents($track->url);

            header('Content-Type: audio/mpeg');
            header('Content-Disposition: filename="'.urlencode($track->artist.' - '.$track->title).'_mp3ler.biz_.mp3"');
            header('Cache-Control: no-cache');
            header("Content-Transfer-Encoding: chunked");
            header('Content-length: '.strlen($file));

            echo $file;
        }
        catch(Exception $e)
        {
            echo '404';
        }
    }
}