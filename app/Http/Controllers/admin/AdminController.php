<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
  public function index()
  {
    return view('admin.login',['title' => 'Admin Login']);
  }

  public function login(Request $request)
  {
      $validator = Validator::make($request->all(), [ //เช็คการรับค่า
          'email' => 'required|email',
          'password' => 'required',
      ]);

      if ($validator->passes()) { //จริง กรอกแล้ว
          if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if(Auth::guard('admin')->user()->role !='admin'){
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error' ,'You are not authorize to access this.');
            }
              return redirect()->route('admin.dashboard');
          } else { // email,password wrong 
              return redirect()->route('admin.login')->with('error' ,'Either email or password is incorrect.');
          }
      } else {
          return redirect()->route('admin.login') // ไม่จริง ไม่กรอกอะไร โชว์error 
              ->withInput()
              ->withErrors($validator);
      }
  }

  public function logout()
    {
        Auth::guard('admin') -> logout();
        return redirect()->route('admin.login');
    }

}
