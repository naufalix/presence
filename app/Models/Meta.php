<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    
    public static $data_meta = [
        'description' => 'Lorem ipsum dolor sit amet',
        'keywords'    => 'Lorem, Ipsum, Dolor Sit, Amet',
        'type'        => 'page',
        'title'       => 'Dashboard',
        'url'         => 'https://naufal.dev',
        'site_name'   => 'Admin Dashboard'
    ];

}
