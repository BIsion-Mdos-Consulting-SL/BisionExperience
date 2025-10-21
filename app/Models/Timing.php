<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    protected $table = 'timing';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'evento_id'
    ];

    public function timings()
    {
        return $this->hasMany(Timing::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class , 'evento_id', 'id');
    }
}
