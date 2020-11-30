<?php

namespace recranet\contactformrecaptcha\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string|null
     */
    public $siteKey;

    /**
     * @var string|null
     */
    public $secretKey;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['siteKey', 'secretKey'], 'required'],
        ];
    }
}
