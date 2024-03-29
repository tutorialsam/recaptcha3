## Recaptcha
1. Install composer package
```bash
composer require tutorials/recaptcha3
```

2. Define service provider in `config/app.php`
```php
'providers' => [
    ...
    Tutorials\Recaptcha3\RecaptchaServiceProvider::class
],
```

3. Define variables in `.env` file and put values from [recaptcha admin panel](https://www.google.com/recaptcha/admin/create)
```
RECAPTCHA_ENABLED=
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

4. Put `recaptcha` middleware on route
```php
Route::post('/login', LoginController::class)->middleware('recaptcha');
Route::post('/register', LoginController::class)->middleware('recaptcha:0.6');
```

5. Insert `@recaptcha` blade directive in the layout
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Recaptcha</title>
        
        @recaptcha
    </head>
</html>
```

6. Wrap your form submit logic with recaptcha validation
```js
window.recaptcha('action_name_for_recaptcha_statistic').then((token) => {
    // form submition logic
    form.transform((data) => ({
        ...data,
        recaptcha_token: token, // recaptcha_token is required
    })).post(route('login'))
});
```

7. Run `php artisan view:clear` to clear the view cache
