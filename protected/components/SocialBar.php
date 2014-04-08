<?php

/**
 * Виджет ссылок для публикации на сторонние сайты
 */
class SocialBar extends CWidget
{
    /**
     * @var array список сайтов
     */
    protected $socials = array(
        'facebook' => array(
            'icon' => 'facebook.png',
            'link' => 'https://www.facebook.com/sharer/sharer.php?s=100&u={url}',
        ),
        'vkontakte' => array(
            'icon' => 'vk.png',
            'link' => 'http://vk.com/share.php?url={url}',
        ),
        'googleplus' => array(
            'icon' => 'google.png',
            'link' => 'https://plus.google.com/share?url={url}',
        ),
        'surfingburd' => array(
            'icon' => 'surfbird.png',
            'link' => 'http://surfingbird.ru/share?url={url}',
        ),
        'odnoklassniki' => array(
            'icon' => 'odnoklassniki.png',
            'link' => 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={url}',
        ),
        'twitter' => array(
            'icon' => 'twitter.png',
            'link' => 'https://twitter.com/share?url={url}',
        ),
        'mail' => array(
            'icon' => 'mail.png',
            'link' => 'http://connect.mail.ru/share?url={url}',
        ),
        'yandex' => array(
            'icon' => 'yandex.png',
            'link' => 'http://my.ya.ru/posts_share_link.xml?url={url}',
        )
    );

    public function run()
    {
        $socials = $this->socials;

        foreach($socials as &$social)
        {
            $social = str_replace('{url}', urlencode(Yii::app()->createAbsoluteUrl(Yii::app()->request->url)), $social);
        }

        $this->render('socialBar', array(
            'socials' => $socials,
        ));
    }
}