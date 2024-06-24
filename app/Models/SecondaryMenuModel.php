<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryMenuModel extends Model
{
    use HasFactory;
    protected $table = 'menus_secondaire';
    protected $fillable = [
        'name',
        'path',
        'type',
        'setting',
        'icon',
    ];
}
