<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
      public function savelocation(Request $request){

      	$driver_status       =       Driver::where("user_id", $request->user_id)->first();
      	if(is_null($driver_status)) {

      		 $driverDataArray          =       array(
            
            "latitude"          =>          $request->latitude,
            "longitude"           =>          $request->longitude,
            "user_id"            =>          $request->user_id,
            
        );


        $driver                   =           Driver::create($driverDataArray);
        if(!is_null($driver)) {
            return response()->json(["status" => 200, "message" => "Location saved "]);
        }

        else {
            return response()->json(["status" => "failed", "message" => "failed to save location"]);
        }

      	}

      	else {

      		$driver_status->longitude = $request->longitude;
      		$driver_status->latitude = $request->latitude;
      		$driver_status->save();
      		return response()->json(["message"=>"location updated successfully"]);

      	}


      } 

      public function online_status(Request $request){

      	$driver       =       Driver::where("user_id", $request->user_id)->first();
        if($driver->status==='online')
          $driver->status='offline';
        else if($driver->status==='offline')
          $driver->status='online';
      	//$driver->status= "online";
      	$driver->save();
      	return response()->json(["status"=>200,"message"=>$driver->status]);


      }


      public function check(Request $request){
        $driver       =       Driver::where("user_id", $request->user_id)->first();
        return response()-> json(["status"=>$driver->status]);
      }

      public function getdrivers(Request $request){
         
          $drivers = Driver::selectRaw(" latitude, longitude,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$request->latitude, $request->longitude, $request->latitude])
            ->where('status', '=', 'online')
            ->having("distance", "<", 400)
            ->get();

             if(!is_null($drivers)){

        return response()->json(["message"=>"drivers fetched", "drivers"=> $drivers]);
    }


      }
}