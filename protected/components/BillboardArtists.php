<?php

/**
 * Виджет топа исполнителей
 */
class BillboardArtists extends CWidget
{
    public function run()
    {
        $criteria = new CDbCriteria(array(
            'order' => 'position ASC',
            'condition' => 'position IS NOT NULL',
            'limit' => 5,
        ));

        $artists = BillboardArtist::model()->cache(3600)->findAll($criteria);

        $this->render('billboardArtists', array(
            'artists' => $artists,
        ));
    }
}