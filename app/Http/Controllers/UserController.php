<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //Medtod get login
    public function index()
    {
        return view('login');
    }
     //Medtod post login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [ //เช็คการรับค่า
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) { //จริง กรอกแล้ว
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.dashboard');
            } else { // email,password wrong 
                return redirect()->route('account.login')->with('error' ,'Either email or paaword is incorrect.');
            }
        } else {
            return redirect()->route('account.login') // ไม่จริง ไม่กรอกอะไร โชว์error 
                ->withInput()
                ->withErrors($validator);
        }
    }
    //Medtod get register
    public function register()
    {
        return view('register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [ //เช็็คการส่งฟอร์ม
            'name' => 'required',
            'email' => 'required|email|unique:users', 
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); //แปลงเป็นรหัสลับ
            $user->role = 'customer';
            $user->save(); //เอาข้อมูลไปเก็บใน medtod save
            return redirect()->route('account.login')->with('success','You have registed successfully!.'); //เอาค่ามาโชว์ในหน้า login เมื่อรีจิตเตอร์ถูก

        } else {
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors($validator);
        }
    }
     
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

}