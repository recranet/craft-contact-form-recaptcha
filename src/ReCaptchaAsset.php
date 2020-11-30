<?php

namespace recranet\contactformrecaptcha;

use craft\web\AssetBundle;

class ReCaptchaAsset extends AssetBundle
{
    public function init()
    {
        $this->js = [
            'https://www.google.com/recaptcha/api.js?render=explicit',
        ];

        $this->jsOptions = [
            'async' => true,
            'defer' => true,
        ];

        parent::init();
    }
}
