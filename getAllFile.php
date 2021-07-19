<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;


/**
 * Class LanguageController.
 */
class SettingController extends Controller
{
  /**
   * @param $locale
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function index()
  {
    $userId = Auth::id();
    $user_type = DB::table('users')->where('id', $userId)->select('user_type')->first();

    $custom_links = DB::table('custom_links')->where(['user_id' => $userId, 'user_type' => '1'])->get();
   
    $custom_linksabc = DB::table('custom_links')->where(['user_id' => $userId, 'user_type' => '2'])->get();

    return view('frontend.setting')->with(['userid' => $userId, 'user_type' => $user_type, 'custom_links' => $custom_links, 'custom_linksabc' => $custom_linksabc]);
  }
  
// start pdf code by lb 

 public function userpdffile(Request $request)
  {
     if($request->hasfile('pdf'))
                {       $userId = Auth::id();
                        $userfirst = User::where('id', $userId)->first();
                     $image = $request->file('pdf');
                     $name =  $image->getClientOriginalName();
                    $img_name = pathinfo($name, PATHINFO_FILENAME);
                    $img_ext = pathinfo($name, PATHINFO_EXTENSION);
                    $fullname = $img_name.'lb_lb'. rand(10, 1000) . "." . $img_ext;
                    $dpath = 'images/pdf/'.$userId.'/'.$fullname;
                    $image->move('images/pdf/'.$userId.'/', $fullname);
                    $userfirst->pdf = $dpath;
                    $userfirst->save();
                    return response(array("status" => true, "message" =>" Upload pdf successfully "), 200)->header("Content-Type", "application/json");
                }
                else{
            return response(array("status" => false, "message" => " No selected file."), 401)->header("Content-Type", "application/json");  
        }
  }
  
   public function userallpdffile(Request $request)
  {
        $userId = Auth::id();
        $userfirst = User::where('id', $userId)->first();
     $pdfData = array();
     $name = []; 
      $path = []; 
        if($userfirst)
                {  
                 $directory = public_path('images/pdf/'.$userId);
                   $pdfs = File::files($directory);
                   foreach($pdfs as $pdf){
                      // $fname2 = $pdfdata->getFilename());
                       $fname = explode("lb_lb",$pdf->getFilename());
                       $fpath  = explode("public/",$pdf->getPathname());
                       $opath = URL::to('/')."/".$fpath[1];
                        $helparray = array("name"=>$fname[0],"path"=>$opath);
                        array_push($pdfData,$helparray);
                   }
                   
                    return response(array("status" => true, "message" =>" fetch all pdf successfully.","data"=>$pdfData), 200)->header("Content-Type", "application/json");
                }
                else{
            return response(array("status" => false, "message" => " No selected file.","data"=>""), 401)->header("Content-Type", "application/json");  
        }
  }
  
//   end pdf code by lb


}