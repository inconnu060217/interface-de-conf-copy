<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPointTypeModel extends Model
{
    use HasFactory;
    protected $table = 'type_point_entree';
    protected $primaryKey = 'id_planning';
    protected $fillable = [
        'nom',
    ];
}
