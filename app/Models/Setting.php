<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'logo_path',
        'email',
        'contact_number',
        'login_background_color',
        'login_background_image',
    ];
}
