<?php

class SocialBar extends CWidget
{
    protected $socials = array(
        'facebook' => array(
            'icon' => 'facebook.png',
            'link' => '',
        ),
        'vkontakte' => array(
            'icon' => 'vk.png',
            'link' => '',
        ),
        'googleplus' => array(
            'icon' => 'google.png',
            'link' => '',
        ),
        'surfingburd' => array(
            'icon' => 'surfbird.png',
            'link' => '',
        ),
        'odnoklassniki' => array(
            'icon' => 'odnoklassniki.png',
            'link' => '',
        ),
        'twitter' => array(
            'icon' => 'twitter.png',
            'link' => '',
        ),
        'mail' => array(
            'icon' => 'mail.png',
            'link' => '',
        ),
        'yandex' => array(
            'icon' => 'yandex.png',
            'link' => '',
        )
    );

    public function run()
    {
        $this->render('socialBar', array(
            'socials' => $this->socials,
        ));
    }
}