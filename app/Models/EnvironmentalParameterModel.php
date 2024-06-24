<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalParameterModel extends Model
{
    use HasFactory;
    protected $table = 'parametrage_environnement';
    protected $fillable = [
        'token_ws_foliateam',
        'url_ws_foliateam',
        'audio_tools_kiamo',
        "speech_recognition_kiamo",
        'tts',
        'appel',
        'email',
        'chat',
        'messaging',
        'accueil'
    ];
}
