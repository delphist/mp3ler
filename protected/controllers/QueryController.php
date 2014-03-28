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

        $this->search_query = $query->text;

        $this->render('view', array(
            'query' => $query,
        ));
    }
}