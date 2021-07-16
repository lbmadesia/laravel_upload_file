<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class registerEmployee extends Controller
{
    protected $data;
    protected $db;
    protected $other;
    // start rigster new employee
    public function store(Request $request){
        $this->data = $request->all();
        $email = $this->data["email"];
        // try{
            $this->db = new Employees();
            $this->other = $this->db->where("email",$email)->get();
            if(count($this->other) != 0){
                return response(array("status"=>"error","message"=>$email." is already exist.","data"=>$this->other),200)->header("Content-Type","application/json");
            }
            else{
                $id_filepath = "";
                $academicQFilespath = [];
                $professionalQFilespath = [];
                $experienceFilespath = [];
                if($request->hasfile('id_file'))
                {      
                     $file = $request->file('id_file');
                       $filename = $file->getClientOriginalName();
                       $dpath = "Employee/".$email."/".$this->data["id_name"];
                       $id_filepath = "storage/app/public/".$dpath."/".$filename;
                       $file->storeAs("public/".$dpath, $filename);
                   $this->data["id_file"] = $id_filepath;
                }
            if ($request->hasfile('avtar')) {
                $file = $request->file('avtar');
                $filename = $file->getClientOriginalName();
                $dpath = "Employee/" . $email;
                $id_filepath = "storage/app/public/" . $dpath . "/avtar.png";
                $file->storeAs("public/" . $dpath, "avtar.png");
                $this->data["avtar"] = $id_filepath;
            }
                if($request->hasfile('academicQFiles'))
                 {
                    foreach($request->file('academicQFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/academicQFiles";
                        $academicQFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["academicQFiles"] = json_encode($academicQFilespath);
                 }
                 if($request->hasfile('professionalQFiles'))
                 {
                    foreach($request->file('professionalQFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/professionalQFiles";
                        $professionalQFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["professionalQFiles"] = json_encode($professionalQFilespath);
                 }
                 if($request->hasfile('experienceFiles'))
                 {
                    foreach($request->file('experienceFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/experienceFiles";
                        $experienceFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["experienceFiles"] = json_encode($experienceFilespath);
                 }
                 
                $this->db->create($this->data);
                return response(array("status"=>"success","message"=>"Employee added.","data"=>$academicQFilespath),200)->header("Content-Type","application/json");
            }
        // }
        // catch(QueryException $error){
        //     return response(array("status"=>"error","message"=>"Opps! employee not added."),500)->header("Content-Type","application/json");
        // }
    }
    // end register new employeee

// star update employeee
public function edit(Request $request){
        $this->data = $request->all();
        $email = $this->data["email"];
        $id = $this->data["id"];
        unset($this->data["_token"]);
        unset($this->data["id"]);
        $data = array();
        
        try{
                 $id_filepath = "";
                $academicQFilespath = [];
                $professionalQFilespath = [];
                $experienceFilespath = [];
                if($request->hasfile('id_file'))
                {      
                     $file = $request->file('id_file');
                       $filename = $file->getClientOriginalName();
                       $dpath = "Employee/".$email."/".$this->data["id_name"];
                       $id_filepath = "storage/app/public/".$dpath."/".$filename;
                       $file->storeAs("public/".$dpath, $filename);
                   $this->data["id_file"] = $id_filepath;
                }
                else{
                    unset($this->data["id_file"]);
                }
            if ($request->hasfile('avtar')) {
                $file = $request->file('avtar');
                $filename = $file->getClientOriginalName();
                $dpath = "Employee/" . $email;
                $id_filepath = "storage/app/public/" . $dpath . "/avtar.png";
                $file->storeAs("public/" . $dpath, "avtar.png");
                $this->data["avtar"] = $id_filepath;
            } else {
                unset($this->data["avtar"]);
            }
            if($request->hasfile('academicQFiles'))
                 {
                    foreach($request->file('academicQFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/academicQFiles";
                        $academicQFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["academicQFiles"] = json_encode($academicQFilespath);
                 }
                 else{
                    unset($this->data["academicQFiles"]);
                 }
                 if($request->hasfile('professionalQFiles'))
                 {
                    foreach($request->file('professionalQFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/professionalQFiles";
                        $professionalQFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["professionalQFiles"] = json_encode($professionalQFilespath);
                    
                 }
                 else{
                    unset($this->data["professionalQFiles"]);
                 }
                 if($request->hasfile('experienceFiles'))
                 {
                    foreach($request->file('experienceFiles') as $file)
                    {
                        $filename = $file->getClientOriginalName();
                        $dpath = "Employee/".$email."/experienceFiles";
                        $experienceFilespath[] = "storage/app/public/".$dpath."/".$filename;
                        $file->storeAs("public/".$dpath, $filename);
                    }
                    $this->data["experienceFiles"] = json_encode($experienceFilespath);
                 }
                 else{
                    unset($this->data["experienceFiles"]);
                 }
           
           $this->db = new Employees();
           $this->db->where("id",$id)->update($this->data);
            return response(array("status"=>"success","message"=>"Employee Details  updated.","data"=>""),200)->header("Content-Type","application/json");
            
        }
        catch(QueryException $error){
            return response(array("status"=>"error","message"=>"Opps! Employee Details not updated."),500)->header("Content-Type","application/json");
        }
}
// end update employeee


// start access all employee name and id
public function index(){
    try{
       $this->data = Employees::all();
       if(count($this->data) != 0){
           return response(array("status"=>"success","message"=>"all employee name access.","data"=>$this->data),200)->header("Content-Type","application/json");
       }
       else{
          return response(array("status"=>"error","message"=>"No employee in database access.","data"=>""),200)->header("Content-Type","application/json");
      
       }
    }
    catch(QueryException $error){
        return response(array("status"=>"error","message"=>"Opps! Employee Details not access.","data"=>""),500)->header("Content-Type","application/json");
    }
}
// end access all employee name and id

}
