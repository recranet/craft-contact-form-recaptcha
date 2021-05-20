<?php

namespace recranet\contactformrecaptcha;

use craft\web\AssetBundle;

class ReCaptchaAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@recranet/contactformrecaptcha/resources';

        $this->js = [
            'js/contact-form-recaptcha.min.js',
        ];

        $this->jsOptions = [
            'async' => true,
            'defer' => true,
        ];

        parent::init();
    }
}
