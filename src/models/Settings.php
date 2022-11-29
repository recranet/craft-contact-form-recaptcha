<?php

namespace recranet\contactformrecaptcha\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string|null
     */
    public ?string $siteKey = null;

    /**
     * @var string|null
     */
    public ?string $secretKey = null;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['siteKey', 'secretKey'], 'required'],
        ];
    }
}
