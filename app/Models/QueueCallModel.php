<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueCallModel extends Model
{
    use HasFactory;
    protected $table = 'file_attente';
    protected $primaryKey = 'id_file_attente';
    public $timestamps = false;
    protected $fillable = [
    'id_file_attente',
    'id_groupe',
    'debordement',
    'callback_attente',
    'duree_dissuasion_reel',
    'duree_dissuasion_tae',
    'ratio_agent_attente',
    'id_type_debordement',
    'callback_annonce_time',
    'renvoi',
    'kid_planning_fa',
    'kid_file_attente_debordement',
    'id_debordement_message',
    'numero_presente'
    ];
}
