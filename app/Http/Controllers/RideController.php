<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ride;
use Illuminate\Support\Facades\Validator;
//use getnokri_task;
use DB;

class RideController extends Controller


{

   private $rider_id=0;
    private $driver_id=0;
    private $ride_id=0;
    
   public function postrequest(Request $request){

  

   	    $requestDataArray          =       array(
                     
            "location"          =>          $request->location,
            "latitude"           =>             $request->lat,
            "longitude"           =>             $request->long,
            "rider_id"           =>             $request->id,
            "status"          =>              "active",

             
        );

        $request1                   =           Ride::create($requestDataArray);

        if(!is_null($request1)) {
            return response()->json(["status" => 200, "success" => true,
            "ride_id"=>$request1->id, "message" => "Request sent successfully"]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }

   }

  

   public function checkrequest(Request $request){


    /*$riderequests = Ride::selectRaw("lat,long,rider_id,location,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( lat ) )
                           * cos( radians( long ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( lat ) ) )
                         ) AS distance", [$request->lat, $request->long, $request->lat])
            ->where('status', '=', 'active')
            ->having("distance", "<", 400)
            ->get()
            ->first();*/

            $riderequests =  Ride::selectRaw(" *,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$request->lat, $request->long, $request->lat])
            
            ->having("distance", "<", 400)
            ->where("status","=","active")
            
            ->where("id", ">", $request->ride_id)
            ->first();




            /*$riderequests = Ride::where('status','=','active')
                                 ->where('driver_id','=',0)
                                 ->first();*/

            if(!is_null($riderequests)){

        return response()->json(["status"=>200,"message"=>"requests fetched", "requests"=> $riderequests]);
    }

    else
         return response()->json(["status"=>"failed","message"=>"requests null"]);

   }

   public function confirmride(Request $request){

    if($request->type==='driver'){
         $driver_id= $request->id;
         $ride_id= $request->ride_id;

         $riderequest = Ride::where('id','=',$ride_id)
                             ->where('status','=','active')
                              ->first();

         if($riderequest!=null){

          $riderequest->driver_id=$driver_id;
          $riderequest->status= "driver found";
          $riderequest->save();
         $rider_id= $riderequest->rider_id;
         return response()->json(["msg"=>"booked", "rider_id"=>$rider_id, "driver_id"=>$driver_id]);
       }
       else
       return response()->json(["msg"=>"nope", "rider_id"=>$rider_id, "driver_id"=>$driver_id]);

    }

    else if($request->type==='rider'){

      $rider_id=$request->id;
      $ride_id=$request->ride_id;
      $riderequest = Ride::where('id','=',$ride_id)
                              ->where('status','=','driver found')
                              ->where('rider_id','=',$rider_id)
                              ->first(); 

      if($riderequest!=null)
      {
        $driver_id= $riderequest->driver_id;
        if($driver_id!=0){
        return response()->json(["msg"=>"booked", "rider_id"=>$rider_id, "driver_id"=>$driver_id, 
          "ride_id"=>$riderequest->id
      ]);
      }

      else {
        return response()->json(["msg"=>"notbooked"
      ]);

      }

      }

      else 
        return response()->json(["msg"=>"Request has not been confirmed yet"]);

    }
    
      




   }



}