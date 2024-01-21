<?php

namespace Tutorials\Recaptcha3\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class RecaptchaRequestFailed extends Exception
{
    public function render(): RedirectResponse
    {
        return back()->with('status', 'Recaptcha request failed.');
    }
}
