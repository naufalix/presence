<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\User;
use Illuminate\Http\Request;

class APIController extends Controller
{
  public function user(User $data){
    return ApiFormatter::createApi(200,"Success",$data);
  }
  public function users(){
    return ApiFormatter::createApi(200,"Success",User::all());
  }
}
