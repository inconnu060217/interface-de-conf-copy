<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainMenuModel extends Model
{
    use HasFactory;
    protected $table = 'menus_principal';
    protected $fillable = [
        'name',
        'path',
        'value',
        'icon',
    ];
}
