<?php

namespace recranet\contactformrecaptcha\variables;

use Craft;

class ReCaptchaVariable
{
    public function getRecaptcha($formId)
    {
        $siteKey = $this->getSiteKey();

        if (!$siteKey) {
            return;
        }

        $recaptchaId = 'g-recaptcha-'.uniqid();

        $html = <<<EOF
<div id="$recaptchaId" class="g-recaptcha"></div>

<script type="text/javascript">
    window.addEventListener('load', function() {
        var form = document.getElementById('$formId');
        
        if (typeof recaptchaCallbacks == 'undefined') {
            window.recaptchaCallbacks = [];
        }

        recaptchaCallbacks.push(function() {
            var widgetId = grecaptcha.render('$recaptchaId', {
                'sitekey': '$siteKey',
                'callback': function() {
                    form.submit();
                },
                'size': 'invisible',
                'badge': 'inline'
            });

            form.setAttribute('data-g-recaptcha-id', widgetId);
        });

        form.addEventListener('change', function(e) {
            initRecaptcha();
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (typeof grecaptcha == 'undefined') {
                initRecaptcha();

                alert('reCAPTCHA is not loaded yet, try resubmitting the form');                
                return;
            }
            
            grecaptcha.execute(form.getAttribute('data-g-recaptcha-id'));
        });
    });
</script>
EOF;

        return $html;
    }

    public function getRecaptchaWithCallback($formId, $callback)
    {
        $siteKey = $this->getSiteKey();

        if (!$siteKey) {
            return;
        }

        $recaptchaId = 'g-recaptcha-'.uniqid();

        $html = <<<EOF
<div id="$recaptchaId" class="g-recaptcha"></div>

<script type="text/javascript">
    window.addEventListener('load', function() {
        var form = document.getElementById('$formId');

        if (form.getAttribute('data-g-recaptcha-id')) {
            return;
        }

        if (typeof recaptchaCallbacks == 'undefined') {
            window.recaptchaCallbacks = [];
        }

        recaptchaCallbacks.push(function() {
            var widgetId = grecaptcha.render('$recaptchaId', {
                'sitekey': '$siteKey',
                'callback': '$callback',
                'size': 'invisible',
                'badge': 'inline'
            });
    
            form.setAttribute('data-g-recaptcha-id', widgetId);
        });

        form.addEventListener('change', function(e) {
            initRecaptcha();
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (typeof grecaptcha == 'undefined') {
                initRecaptcha();

                alert('reCAPTCHA is not loaded yet, try resubmitting the form');
                return;
            }
            
            grecaptcha.execute(form.getAttribute('data-g-recaptcha-id'));
        });
    });
</script>
EOF;

        return $html;
    }

    private function getSiteKey()
    {
        $settings = Craft::$app->plugins->getPlugin('contact-form-recaptcha')->getSettings();

        return $settings['siteKey'] ?? null;
    }
}
