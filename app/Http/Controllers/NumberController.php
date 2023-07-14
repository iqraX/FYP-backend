<?php
 //use Twilio\Rest\Client;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Number;
use Illuminate\Support\Facades\Validator;
//require_once '/path/to/vendor/autoload.php'; 
 
//use Vendor\twilio\sdk\src\Twilio\Rest\Client;

 //require_once "Twilio/autoload.php";


class NumberController extends Controller
{

  function send_message ( $post_body, $url, $username, $password) {
  $ch = curl_init( );
  $headers = array(
  'Content-Type:application/json',
  'Authorization:Basic '. base64_encode("$username:$password")
  );
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, 1 );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
  // Allow cUrl functions 20 seconds to execute
  curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
  // Wait 10 seconds while trying to connect
  curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
  $output = array();
  $output['server_response'] = curl_exec( $ch );
  $curl_info = curl_getinfo( $ch );
  $output['http_status'] = $curl_info[ 'http_code' ];
  $output['error'] = curl_error($ch);
  curl_close( $ch );
  return $output;
} 
     
public function save_code(Request $request){

$username = 'iqrajehangir';
$password = 'Reactnativedeveloper11';
    

    /*$sid    = "AC3e7d7d1a9087f0c3c30a90578a59591f"; 
$token  = "87fa79d9782403176fbe2e2e78da1f06"; 
$twilio = new Client($sid, $token);*/

  $random_code = mt_rand(1000,9999);

  $temp       =       Number::where("number", $request->number)->first();
  $message= 'Your verification code for AMBULANCE ON CALL is  ' .$random_code;

        if(is_null($temp)) {

           $numberDataArray          =       array(
            
            "number"          =>          $request->number,
            "code"           =>          $random_code,
            
        );

           $driver                   =           Number::create($numberDataArray);

         }

         else{

          $temp->code=$random_code;
          $temp->save();

          
         }

          $messages = array(
  array('to'=>$request->number, 'body'=>$message));

    $result = $this->send_message( json_encode($messages), 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30', $username, $password );

    if ($result['http_status'] === 201)
    {
      $status_code= 'success';
    }
    else if ($result['http_status'] === 400)
      $status_code= '400';
    else if ($result['http_status'] === 403)
      $status_code= '403';
    else;

  /*  if ($result['http_status'] != 201) {
  print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
} else {
  print "Response " . $result['server_response'];*/
      

    return response()->json(["status" => $status_code, "error" =>$result['error'], "response"=>$result['server_response'] ]);


  }

  


        
     	
     
}