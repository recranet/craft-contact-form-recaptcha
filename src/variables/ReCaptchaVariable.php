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
        
        var widgetId = grecaptcha.render('$recaptchaId', {
            'sitekey': '$siteKey',
            'callback': function() {
                form.submit();
            },
            'size': 'invisible',
            'badge': 'inline'
        });

        form.setAttribute('data-g-recaptcha-id', widgetId);
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
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

        form.addEventListener('change', function(e) {
            if (form.getAttribute('data-g-recaptcha-id')) {
                return;
            }

            var widgetId = grecaptcha.render('$recaptchaId', {
                'sitekey': '$siteKey',
                'callback': '$callback',
                'size': 'invisible',
                'badge': 'inline'
            });
    
            form.setAttribute('data-g-recaptcha-id', widgetId);
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
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
