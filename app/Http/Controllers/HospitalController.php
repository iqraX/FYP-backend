<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Validator;

class HospitalController extends Controller
{
     public function getHospitals(Request $request){

     	$hospitals = Hospital::selectRaw(" name,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$request->latitude, $request->longitude, $request->latitude])
            
            ->having("distance", "<", 400)
            ->get();

             if(!is_null($hospitals)){

        return response()->json(["status"=>200, "message"=>"drivers fetched", "hospitals"=> $hospitals]);
    }


      }

      public function personalize(Request $request){

        $x=0;

        if($request->rating==='Above 2')
          $x=2;
        else if($request->rating==='Above 3')
          $x=3;
        else if($request->rating==='Above 4')
          $x=4;
        else;


        if($request->nature!='')
        {
          if($request->specialization!=''){
          
          $hospitals = Hospital::select()
                      ->where("nature","=",$request->nature)
                      ->where("specialization","=",$request->specialization)
                      ->where("rating",">=",$x)
                      ->get();
                }

            else{

                $hospitals = Hospital::select()
                      ->where("nature","=",$request->nature)
                      ->where("rating",">=",$x)
                      ->get();

            }

          }

          else {
            if ($request->specialization!=''){

               $hospitals = Hospital::select()
                      ->where("specialization","=",$request->specialization)
                      ->where("rating",">=",$x)
                      ->get();

            }
            else
            {
              $hospitals = Hospital::select()
                      ->where("rating",">=",$x)
                      ->get();
            }




          }
          






          if(!is_null($hospitals)){
            

        return response()->json(["status"=>200, "message"=>"hospitals fetched", "hospitals"=> $hospitals]);
         }

         else
          return response()->json(["status"=>"none"]);



      } //end of function

     	
     
}