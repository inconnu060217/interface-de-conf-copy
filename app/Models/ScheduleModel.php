<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleModel extends Model
{
    use HasFactory;
    protected $table = 'planning';
    protected $primaryKey = 'id_planning';
    public $timestamps = false;
    protected $fillable = [
        'id_planning',
        'kid_planning',
        'id_groupe',
        'id_banque_message'
    ];
    public function banqueMessage()
    {
        return $this->hasOne('App\Models\BanqueMessage', 'id_banque_message', 'id_banque_message');
    }
}
