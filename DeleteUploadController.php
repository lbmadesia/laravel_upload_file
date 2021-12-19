<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Models\Galary;
use Illuminate\Support\Facades\Storage;

class DeleteUploadController extends Controller
{

    protected $storage;

    public function __construct()
    {
        $this->upload_path = 'image' . DIRECTORY_SEPARATOR . 'galary' . DIRECTORY_SEPARATOR;
        $this->storage = Storage::disk('public');
    }
  
    public function index()
    {
        $galaries = Galary::all();
        return view('backend.galary.index',compact('galaries'));
    }


    public function create()
    {
        return view('backend.galary.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input = $this->uploadImage($input);
        if($galary = Galary::create($input))
          return redirect()->route('admin.galary.index')->with('flash_success', __('Galary was successfully created'));
        else
            return back()->with('flash_error', __('Opps! Failed'));
       
    }


    public function edit(Galary $galary)
    {
        return view('backend.galary.edit',compact('galary'));
    }


    public function update(Galary $galary, Request $request)
    {
        $input = $request->all();
        if (array_key_exists('image', $input)) {
            $this->deleteOldFile($galary);
            $input = $this->uploadImage($input);
        }
        if ($galary = $galary->update($input))
            return redirect()->route('admin.galary.index')->with('flash_success', __('Galary was successfully updated'));
        else
            return back()->with('flash_error', __('Opps! Failed'));
    }


    public function destroy(Galary $galary)
    {
        $this->deleteOldFile($galary);
        $galary->delete();
        return redirect()->route('admin.galary.index')->with('flash_success', __('Galary was successfully deleted'));
    }

    public function uploadImage($input)
    {
        $image = $input['image'];
        if (isset($input['image']) && !empty($input['image'])) {
            $name =  $image->getClientOriginalName();
            $img_name = pathinfo($name, PATHINFO_FILENAME);
            $img_ext = pathinfo($name, PATHINFO_EXTENSION);
            $fullname = $img_name . rand(10, 1000) . "." . $img_ext;
            $image->move('images/galary/', $fullname);
            $input['image'] = "images/galary/" . $fullname;
            return $input;
        }
    }
    public function deleteOldFile($model)
    {
        $fileName = $model->featured_file;

        return $this->storage->delete($this->upload_path . $fileName);
    }
}
