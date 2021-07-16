<?php 
// FIRST WAY ==========================
$x = 0;
            foreach ($request->imagecustom as $imagenew) {
                $image = $imagenew;
                $name =  $image->getClientOriginalName();
                $img_name = pathinfo($name, PATHINFO_FILENAME);
                $img_ext = pathinfo($name, PATHINFO_EXTENSION);
                $fullname = $img_name . rand(10, 1000) . "." . $img_ext;
                $image->move('images/customLinks/', $fullname);
                $imagedata = "images/customLinks/" . $fullname;
                if ($request->user_type[$x] == "1") {
                    DB::table('custom_links')->insert(array('user_id' => $user->id, 'name' => $request->name[$x], 'link' => $request->link[$x], 'image' => $imagedata, 'user_type' => '1'));
                } else {
                    DB::table('custom_links')->insert(array('user_id' => $user->id, 'name' => $request->name[$x], 'link' => $request->link[$x], 'image' => $imagedata, 'user_type' => '2'));
                }
                $x++;
            }

// SECOND WAY ===========================

 if($request->hasfile('pdf'))
                {      
                     $userfirst = User::where("id", $request->id)->first();
                     $image = $request->file('pdf');
                     $name =  $image->getClientOriginalName();
                    $img_name = pathinfo($name, PATHINFO_FILENAME);
                    $img_ext = pathinfo($name, PATHINFO_EXTENSION);
                    $fullname = $img_name . rand(10, 1000) . "." . $img_ext;
                    $dpath = 'images/pdf/'.$fullname;
                    $image->move('images/pdf/', $fullname);
                    $userfirst->pdf = $dpath;
                    $userfirst->save();
                    return response(array("status" => true, "message" =>" Upload pdf successfully "), 200)->header("Content-Type", "application/json");
                }
                else{
            return response(array("status" => false, "message" => " No selected file."), 401)->header("Content-Type", "application/json");  
        }