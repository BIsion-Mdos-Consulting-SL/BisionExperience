<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Restaurante extends Model
{
    protected $table = 'restaurante';
    public $timestamps = false;

    protected $casts = [
        'evento_id' => 'int'
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'foto_restaurante',
        'enlace',
        'evento_id'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id' , 'id');
    }
}
