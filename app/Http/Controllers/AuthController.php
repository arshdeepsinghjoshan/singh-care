<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }
    public function login()
    {
        try {
            $model = new User();
            return view('login', compact('model'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function authenticate(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required|min:8',
                    'email' => 'required|email|exists:users'
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withInput()->with('error',  $validator->messages()->first());
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'state_id' => User::STATE_ACTIVE])) {
                return  redirect('/')->with('success', 'Login Successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Invalid credentials');
            }
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function register()
    {
        try {
            $model = new User();
            return view('register', compact('model'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    protected static function validator(array $data, $login = false)
    {
        $rules = [
            "name" => "required|max:50",
            "email" => "required|email|unique:users|max:100",
            "password" => ["required", "string", 'min:8'],
            "confirm_password" => "required|same:password",
        ];
        return Validator::make($data, $rules);
    }

    public function registration(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($this->validator($request->all())->fails()) {
                $message = $this->validator($request->all())->messages()->first();
                return redirect()->back()->withInput()->with('error', $message);
            }
            $model = new User();
            $model->fill($request->all());
            $model->role_id = User::ROLE_USER;
            $model->state_id = User::STATE_INACTIVE;
            $model->created_by_id = 1;
            $model->generateEmailOtp();
            $model->generateActivationkey();
            $model->password = Hash::make($request->password);
            if ($model->save()) {
                $model->sendRegistrationMailtoUser();
                $walletModel = new Wallet();
                $walletModel->state_id = Wallet::STATE_INACTIVE;
                $walletModel->created_by_id = $model->id;
                $walletModel->generateWalletNumber();
                if (!$walletModel->save()) {
                    DB::rollBack();
                    return redirect('/')->with('error', 'Unable to save the User!');
                }
                DB::commit();

                return redirect('/user/confirm-email/' . $model->activation_key)->with('success', 'Registration successfully, Please verify your email address!');
            } else {
                DB::rollBack();
                return redirect('/')->with('error', 'Unable to save the User!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
