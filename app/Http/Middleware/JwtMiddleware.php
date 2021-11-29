<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use DateTime;
use Tymon\JWTAuth\Facades\JWTAuth;


class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // $cektoken = JWTAuth::getToken();
            $cektoken = new \Tymon\JWTAuth\Token($request->bearerToken());

            if (empty($cektoken) || $cektoken == '' || $cektoken == null) {
                return response()->json([
                    'status' => 'unauthorized',
                    'code' => 500,
                ]);
            }

            $explode = explode('.', $cektoken);
            $tokenheader = base64_decode($explode[0]);
            $tokenpayload = base64_decode($explode[1]);
            $jwtheader = json_decode($tokenheader);
            $jwtpayload = json_decode($tokenpayload);
            if (empty($jwtheader) || $jwtheader == null || empty($jwtpayload) || $jwtpayload == null) {
                return response()->json(['Token is not valid'], 500);
            }

            $expired = $jwtpayload->exp;
            $humandate = date("Y-m-d H:i:s", $expired);
            $now = new DateTime();

            $cariuser = User::where('api_token', '=', $cektoken)->get();

            $username = [];
            foreach ($cariuser as $user) {
                $username = $user->username;
            }

            if ($username != $jwtpayload->user_data->username) {
                return response()->json(['Token is not valid'], 500);
            } else if ($now->getTimestamp() > $jwtpayload->exp) {
                return response()->json(['Token is expired'], 500);
            } else {
                return $next($request);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['Token Expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }
    }
}
