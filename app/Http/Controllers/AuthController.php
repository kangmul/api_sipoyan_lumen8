<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\RuleLabel;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\False_;

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
            $cekpassword = Hash::check($password, $user->password);
            if ($cekpassword) {
                if ($user->is_active) {
                    return response()->json([
                        'status' => 'Success',
                        'code' => 200,
                        'message' => 'Anda berhasil login',
                        'data' => [
                            'user' => $user,
                            'token' => 'token'
                        ],
                    ]);
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
}
