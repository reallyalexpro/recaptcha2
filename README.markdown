# reCAPTCHA

## Installation
1. Get reCAPTCHA2 API keys from https://www.google.com/recaptcha/
2. Upload the 'recaptcha2' folder in this archive to your Symphony 'extensions' folder.
3. Enable it at System > Extensions.
4. Go to System > Preferences and enter your reCAPTCHA2 private/public API key pair.
5. Add the "reCAPTCHA2 Verification" filter rule to your Event via Blueprints > Events
6. Save the Event.
7. Add "reCAPTCHA2: Public Key" Data Source to your page.
8. Add the following line to your page: 

```HTML    
in head
<script src='https://www.google.com/recaptcha/api.js'></script>
in form
<div class="g-recaptcha" data-sitekey="your-public-key"></div>

```

