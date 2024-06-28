<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPointModel extends Model
{
    use HasFactory;
    protected $table = 'point_entree';
    protected $primaryKey = 'id_point_entree';
    public $timestamps = false;
    protected $fillable = [
        'sda',
        'kid_file_attente',
        'id_type_point_entree',
        'numero_presente',
        'kid_svi',
        'renvoi'
    ];
}
