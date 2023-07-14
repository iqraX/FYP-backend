<?php

namespace App\Http\Controllers;
use App\Models\Admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    function login(){
    	return view('backend.login');
    }

    function submit_login(Request $request){
    	$request->validate([
    		'username'=>'required',
    		'password'=>'required'
    	]);

    	$userCheck=Admin::where(['username'=>$request->username,'password'=>$request->password])->count();
    	if($userCheck>0){
            $adminData=Admin::where(['username'=>$request->username,'password'=>$request->password])->first();
            session(['adminData'=>$adminData]);
    		return redirect('admin/dashboard');
    	}else{
    		return redirect('admin/login')->with('error','Invalid username/password!!');
    	}

    }

    function dashboard()
    {
    	return view('main');
    }
}
