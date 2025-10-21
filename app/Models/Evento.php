<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Evento
 * 
 * @property int $id
 * @property string $nombre
 * @property string $marca
 * @property Carbon $fecha
 * @property Carbon $hora
 * @property string $lugar_evento
 * @property string $tipo_evento
 * @property float $coste_evento
 * @property int $aforo
 * @property float $coste_unitario
 * @property string|null $enlace
 * @property string|null $documentacion
 * @property string $texto_invitacion
 * @property string $imagen
 *
 * @package App\Models
 */
class Evento extends Model
{
	protected $table = 'evento';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'hora' => 'datetime',
		'coste_evento' => 'float',
		'aforo' => 'int',
		'coste_unitario' => 'float'
	];

	protected $fillable = [
		'nombre',
		'marca',
		'fecha',
		'hora',
		'lugar_evento',
		'tipo_evento',
		'coste_evento',
		'aforo',
		'coste_unitario',
		'enlace',
		'documentacion',
		'texto_invitacion',
		'imagen'
	];

	public function invitados()
	{
		return $this->belongsToMany(Conductor::class, 'evento_conductor', 'evento_id', 'conductor_id');
	}

	public function marcas()
	{
		return $this->belongsToMany(Marca::class, 'eventos_marca', 'evento_id', 'marca_id');
	}

	public function coches()
	{
		return $this->hasMany(Coch::class, 'evento_id');
	}

	public function paradas()
	{
		return $this->hasMany(Parada::class, 'evento_id')->orderBy('orden');
	}

	public function restaurante()
	{
		return $this->hasOne(Restaurante::class, 'evento_id', 'id');
	}

	public function timings()
	{
		return $this->hasMany(Timing::class, 'evento_id', 'id');
	}

	public function banners()
	{
		return $this->hasMany(Banner::class, 'evento_id', 'id');
	}
}
