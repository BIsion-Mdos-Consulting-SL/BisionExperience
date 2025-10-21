<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $primaryKey = 'id';

    protected $fillable = [
        'evento_id',
        'empresa',
        'enlace',
        'video',
        'imagen',
        'frase',
        'contacto',
        'texto'
    ];

    public function evento(){
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
