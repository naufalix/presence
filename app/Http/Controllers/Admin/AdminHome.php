<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Meta;
use App\Models\User;
// use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminHome extends Controller
{

    private function meta(){
        $meta = Meta::$data_meta;
        $meta['title'] = 'Admin | Home';
        return $meta;
    }

    public function index(){
        return view('admin.home',[
            "meta" => $this->meta(),
            // "vehicles" => Vehicle::orderBy("name","ASC")->get(),
        ]);
    }

    public function postHandler(Request $request){
        if(!Privilege::get(6)){
            return redirect('/dev/home')->with("info","You dont have access");
        }
        if($request->submit=="store"){
            $res = $this->store($request);
            return redirect('/dev/admin')->with($res['status'],$res['message']);
        }
        if($request->submit=="update"){
            $res = $this->update($request);
            return redirect('/dev/admin')->with($res['status'],$res['message']);
        }
        if($request->submit=="destroy"){
            $res = $this->destroy($request);
            return redirect('/dev/admin')->with($res['status'],$res['message']);
            // return redirect('/dashboard/user')->with("info","Fitur hapus sementara dinonaktifkan");
        }else{
            return redirect('/dev/admin')->with("info","Submit not found");
        }
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name'=>'required',
            'username' => 'required',
            'password' => 'required',
            'status' => 'required',
            'privilege'=>'required',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['privilege'] = implode(",",$validatedData['privilege']);
        //dd($validatedData);

        // Check Username
        if(!Admin::whereUsername($request->username)->first()){
            // Create new user
            Admin::create($validatedData);
            return ['status'=>'success','message'=>'Admin added successfully'];
        }else{
            return ['status'=>'error','message'=>'Username already taken'];
        }
    }

    public function update(Request $request){
        $validatedData = $request->validate([
            'id'=>'required|numeric',
            'name'=>'required',
            'username' => 'required',
            'status' => 'required',
            'privilege'=>'required',
        ]);
        $validatedData['privilege'] = implode(",",$validatedData['privilege']);

        $admin = Admin::find($request->id);
        $oldUsername = $admin->username;
        $newUsername = $request->username;
        
        //Check if password empty
        if(!$request->password){
            $validatedData['password'] = $admin->password;
        }else{
            $validatedData['password'] = Hash::make($request->password);
        }
        
        //Check if the admin is found
        if($admin){
            //Check username
            if($newUsername!=$oldUsername){
                if(Admin::whereUsername($request->username)->first()){
                    return ['status'=>'error','message'=>'Username already taken'];
                }
            }
            // Update admin
            $admin->update($validatedData);   
            return ['status'=>'success','message'=>'Admin updated successfully']; 
        }else{
            return ['status'=>'error','message'=>'Admin not found'];
        }
    }

    public function destroy(Request $request){
        
        $validatedData = $request->validate([
            'id'=>'required|numeric',
        ]);

        $admin = Admin::find($request->id);

        //Check if the admin is found
        if($admin){
            Admin::destroy($request->id);    // Delete admin
            return ['status'=>'success','message'=>'Admin deleted successfully'];
        }else{
            return ['status'=>'error','message'=>'Admin not found'];
        }
    }
}
