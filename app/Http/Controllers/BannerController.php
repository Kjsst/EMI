<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::get();
        return view('banner', [
            'banners' => $banners,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'banner' => 'required',
        ]);
        $banner = new Banner;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = "banner/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $banner['banner'] = $filename;
        }
        $banner->url = $request->url;
        $banner->save();

        return redirect()->route('banner')->with('success','customer created successfully');
    }

    public function edit($id){
        $banner = Banner::where('id',$id)->first();
        return view('editbanner', [
            'banner' => $banner
        ]);
    }

    public function update(Request $request,$id){
        $banner = Banner::find($id);
        if ($request->hasFile('banner')) {
            $oldFile = $banner->banner;
            $file = $request->file('banner');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "banner/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $banner->banner = $filename;
        }
        $banner->url = $request->url;
        $banner->update();

        return redirect()->route('banner')->with('success','banner updated successfully');
    }

    public function delete($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            $banner->delete();
            return redirect()->route('banner')->with('success','Banner deleted successfully');
        }
        else{
            return redirect()->route('banner')->with('error','Banner not found');
        }
    }

    public function createBanner(Request $request){

        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'banner' => 'required',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $banner = new Banner;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = "banner/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $banner['banner'] = $filename;
        }
        // $banner->banner = $request->banner;
        $banner->save();

        return response()->json(['message' => 'Banner Successfully Added.','data'=>$banner], 200);
    }

    public function banners(){
        // $id = auth()->user()->id;
        $banners = Banner::all();

        if (count($banners) > 0) {
            return response()->json(['message' => 'Baneers data', 'data' => $banners], 200);
        }
        return response()->json(['message' => 'No Baneers found'], 422);
    }
}