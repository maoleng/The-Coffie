<?php

namespace App\Http\Middleware\ApiAuthenticate;

use App\Models\Device;
use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($authorization = $request->headers->get('Authorization')) {
            preg_match("/bearer ([^\ ]*)/i", $authorization, $match);
            $token = $match[1] ?? null;
        } else {
            $token = $request->query->get('token');
        }

        $device = services()->deviceService()->where('token', $token)->with('user')->first();

        if (!$device instanceof Device) {
            return $this->errorJson(Response::HTTP_UNAUTHORIZED);
        }

        $user = $device->user;
        if (!$user instanceof User || !$user->is_active) {
            return $this->errorJson(Response::HTTP_UNAUTHORIZED);
        }

        if ($user->tz) {
            @date_default_timezone_set($user->tz);
        }

        App::singleton('authed', function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    protected function errorJson($statusCode = Response::HTTP_BAD_REQUEST): Response
    {
        return new JsonResponse([
            'status' => false,
            'message' => 'Ê, mày là ai vậy ?',
        ], $statusCode);
    }
}
