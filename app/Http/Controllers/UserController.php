<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $status_code    =        200;

    public function userSignUp(Request $request) {
        
        $validator              =        Validator::make($request->all(), [
            //"type"              =>          "required",
            //"no"              =>          "required",
            "first"              =>          "required",
            //"last"             =>          "required|email",
            //d"password"          =>          "required",
            
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }

        

        $userDataArray          =       array(
            //"first_name"         =>          $first_name,
            //"last_name"          =>          $last_name,
            "first_name"          =>          $request->first,
            "last_name"           =>          $request->last,
            "password"           =>          md5($request->password),
            "email_address"              =>          $request->email,
            "type"               =>           $request->type,
            "phone"              =>          $request->no
        );

/*
        $user_status            =           User::where("email", $request->email)->first();

        if(!is_null($user_status)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! email already registered"]);
        }*/



        $user                   =           User::create($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => $this->status_code, "id" => $user->id, "message" => "Registration completed successfully", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }

        

        
        
    }


    // ------------ [ User Login ] -------------------
    public function userLogin(Request $request) {

        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required",
                "password"          =>          "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed","message" => "validation error", "validation_error" => $validator->errors()]);
        }


        // check if entered email exists in db
        $email_status       =       User::where("email_address", $request->email)->first();


        // if email exists then we will check password for the same email

        if(!is_null($email_status)) {
            $password_status    =   User::where("email_address", $request->email)->where("password", md5($request->password))->first();

            // if password is correct
            if(!is_null($password_status)) {
                $user           =       $this->userDetail($request->email);

                return response()->json(["status" => 200, "id" => $user->id,"type" => $user->type,
                 "message" => "You have logged in successfully",]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
            }
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
        }
    }

    // ------------------ [ User Detail ] ---------------------
    public function userDetail($email) {
        $user               =       array();

        if($email != "") {
            $user           =       User::where("email_address", $email)->first();
            return $user;
        }
    }

    public function changePassword(Request $request){

        $email_status       =       User::select()
                                          ->where("email_address", $request->email)
                                          ->where("password", md5($request->password))
                                          ->first();

        if(!is_null($email_status)) {
                 
                 $email_status->password = md5($request->new_password);
                 $email_status->save();

                return response()->json(["status" => 200, 
                 "message" => "Password changed successfully",]);
            }

            else{
                return response()->json(["status" => 'failed', 
                 "message" => "Authntication failed",]);
            }

           
        

    }

     public function getDetail(Request $request) {
        $user               =       array();
        
            $user           =       User::where("email_address", $request->email)->first();
            if(!is_null($user)) {
            return response()->json(["status" => 200, 
             "first" => $user->first_name, "last" => $user->last_name, "phone" => $user->phone,
             "id" => $user->id, "type" => $user->type,"message" => "found",
             
         ]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "wrong id"]);
        }

        
    }

    public function editDetail(Request $request) {

    	$kind= $request->kind;
    	$value= $request->value;
    	$email= $request->email;

    	$user = User::where('email_address',$email)->first();
    	if($kind=='First Name')
    	    $user->first_name = $value;
        else if($kind=='Last Name')
        	$user->last_name = $value;
        else if($kind=='Phone Number')
        	$user->phone = $value;
        else if($kind=='Email')
        	{
        		$temp = User::where('email_address',$value)->first();
        		if(!is_null($user)) {
        			 return response()->json(["status"=>"failed","message"=>"Account with this email already exists"]);
        			}
        		else
        			$user->email_address = $value;
        		

        	}
        	else;


    	$user->save(); 
    	/*$stmt= 'update users set '.$kind.' = ? where email_address = ?';
        User::update($stmt,[$value,$email]);*/
        return response()->json(["message"=>"Updated successfully"]);


    }

    public function deleteUser(Request $request){
        $email= $request->email;
        $user = User::where('email_address',$email)->first();
                if(!is_null($user)) {

                    $user->delete();
                     return response()->json(["status"=>"success","message"=>"Deleted successfully"]);
                    }
                else
                    return response()->json(["status"=>"failed","message"=>"Could not delete"]);


    }

}

