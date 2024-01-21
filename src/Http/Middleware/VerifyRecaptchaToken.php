<?php

namespace Tutorials\Recaptcha3\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tutorials\Recaptcha3\Exceptions\RecaptchaRequestFailed;
use Tutorials\Recaptcha3\Exceptions\RecaptchaVerificationFailed;

class VerifyRecaptchaToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param float|null $score
     * @return Response
     * @throws RecaptchaRequestFailed
     * @throws RecaptchaVerificationFailed
     */
    public function handle(Request $request, Closure $next, ?float $score = null): Response
    {
        $response = Http::asForm()
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret'),
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ])
            ->object();

        if (! $response || $response->success === false) {
            throw new RecaptchaRequestFailed();
        }

        if ($response->score < $score ?? config('recaptcha.score')) {
            throw new RecaptchaVerificationFailed();
        }

        return $next($request);
    }
}
