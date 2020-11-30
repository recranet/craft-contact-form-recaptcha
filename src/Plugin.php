<?php

namespace recranet\contactformrecaptcha;

use Craft;
use craft\contactform\models\Submission;
use craft\web\twig\variables\CraftVariable;
use ReCaptcha\ReCaptcha;
use recranet\contactformrecaptcha\models\Settings;
use recranet\contactformrecaptcha\variables\ReCaptchaVariable;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    /**
     * @var \ReCaptcha\ReCaptcha
     */
    private $recaptcha;

    public function init()
    {
        parent::init();

        if (\Craft::$app->request->isCpRequest) {
            return;
        }

        $settings = $this->getSettings();

        if (!$settings->siteKey || !$settings->secretKey) {
            return;
        }

        $this->recaptcha = new ReCaptcha($settings->secretKey);

        // Add reCAPTCHA template variable
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $e) {
            /** @var CraftVariable $variable */
            $variable = $e->sender;
            $variable->set('contactFormRecaptcha', ReCaptchaVariable::class);
        });

        // Subscribe to before send to add spam validation
        Event::on(Submission::class, Submission::EVENT_AFTER_VALIDATE, function (Event $e) {
            /** @var Submission $submission */
            $submission = $e->sender;

            $captchaResponse = Craft::$app->request->getParam('g-recaptcha-response');

            if (!$captchaResponse) {
                $submission->addError('recaptcha', 'The reCAPTCHA response is missing.');

                return;
            }

            $verificationResult = $this->recaptcha->verify($captchaResponse, $_SERVER['REMOTE_ADDR']);

            if (!$verificationResult->isSuccess()) {
                $submission->addError('recaptcha', 'The reCAPTCHA verification failed.');

                return;
            }
        });
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }
}
