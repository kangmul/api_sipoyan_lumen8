<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\RuleLabel;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\Constraint\IsTrue;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthController extends Controller
{
    /**
     * Register user ke model user
     */

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:users,username|max:10',
                'nickname' => 'required|max:15',
                'password' => 'required',
                'email' => 'required|unique:users,email',
            ],
            RuleLabel::validate(),
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'code' => 201,
                'message' => $validator->errors()->getMessageBag(),
            ]);
        }

        $getUserrole = $this->getUserRole();

        try {
            $register = new User;
            $register->username = strip_tags($request->input('username'));
            $register->nickname = strip_tags($request->input('nickname'));
            $register->password = Hash::make(strip_tags($request->input('password')));
            $register->email = strip_tags($request->input('email'));
            $register->is_active = false;
            $register->role = $getUserrole->role;
            $register->role_id = $getUserrole->id;

            if ($register->save()) {
                // send email ke administrator dan send email ke user yang bersangkutan

                $data = [
                    'status' => 'Success',
                    'code' => 200,
                    'message' => 'Registrasi berhasil, Aktivasi akun dengan mengkil tombol aktifkan di email anda',
                    'data' => $register,
                ];
            } else {
                $data = [
                    'status' => 'Failed',
                    'code' => 400,
                    'message' => 'Gagal registrasi',
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registrasi akun gagal!'], 401);
        }
    }

    /**
     * Melakukan login dengan username atau email nya
     */
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // cek user
        $user = User::where('username', '=', $username)->first();

        if ($user) {
            // cek password jika valid
            $cekpassword = Hash::check($password, $user->password);
            if ($cekpassword) {
                // cek user jika sudah aktivasi
                if ($user->is_active) {
                    $generateToken = $this->generateToken($request, $user);
                    // update api_token
                    try {
                        $updateuser = User::find($user->id);
                        $updateuser->api_token = $generateToken->token;
                        $updateuser->expired = $generateToken->expired;
                        if ($updateuser->save()) {
                            return response()->json([
                                'status' => 'Success',
                                'code' => 200,
                                'message' => 'Anda berhasil login',
                                'data' => [
                                    'user' => $user,
                                ],
                            ]);
                        }
                    } catch (Exception $e) {
                        return response()->json([
                            'status' => 'Failed',
                            'code' => 401,
                            'message' => 'Gagal update token',
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'Failed',
                        'code' => 400,
                        'message' => 'Aktivasi akun anda !',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'code' => 201,
                    'message' => 'Password yang anda masukan salah',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'Failed',
                'code' => 404,
                'message' => 'Akun tidak ditemukan',
            ]);
        }
    }

    public function getUserRole()
    {
        $user = Role::where(['is_active' => true, 'role' => 'user'])->first();
        return $user;
    }

    private function generateToken($request, $data)
    {
        $now = new DateTime();
        $future = new DateTime("+720 minutes");
        $server = $request->getHttpHost();
        $jti = Crypt::encrypt(rand());
        $customClaims = [
            "exp" => $future->getTimestamp(),
            "user_data" => [
                "username" => $data->username,
                "role" => $data->role,
                "nickname" => $data->nickname,
                "email" => $data->email,
            ],
            "sub" => $server,
            "iss" => "bearer"
        ];

        $payload = JWTFactory::customClaims($customClaims)->make($customClaims);
        $token = JWTAuth::encode($payload);
        $data['token'] = (string)$token;
        $data['expired'] = $future->getTimestamp();
        return $data;
    }
}
