(function(document, id) {
    var head = document.getElementsByTagName('head')[0];

    if (!head) {
        return;
    }

    var loadRecaptcha = function() {
        if (document.getElementById(id)) {
            return;
        }
        
        var scriptElement = document.createElement('script');
        
        scriptElement.setAttribute('src', 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoadedCallback&render=explicit');
        scriptElement.setAttribute('id', id);
        
        head.appendChild(scriptElement);
    };

    window.initRecaptcha = function() {
        if (typeof grecaptcha == 'undefined') {
            loadRecaptcha();
        }
    };

    window.onRecaptchaLoadedCallback = function() {
        if (typeof recaptchaCallbacks == 'undefined') {
            throw new Error('reCAPTCHA callbacks not defined');
        }

        for (i = 0; i < recaptchaCallbacks.length; i++) {
            recaptchaCallbacks[i]();
        }
    };
})(document, 'contact-form-recaptcha');
