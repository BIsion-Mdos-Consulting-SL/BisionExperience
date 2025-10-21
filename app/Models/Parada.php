<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parada
 * 
 * @property int $id
 * @property string $nombre_vehiculo
 * @property string $conductor
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_fin
 * @property string|null $nombre
 * 
 * @property Collection|Reserva[] $reservas
 *
 * @package App\Models
 */
class Parada extends Model
{
	protected $table = 'parada';
	public $timestamps = false;

	protected $fillable = [
		'evento_id',
		'conductor',
		'nombre',
		'descripcion',
		'enlace',
		'orden'
	];

	public function evento()
	{
		return $this->belongsTo(Evento::class, 'evento_id');
	}

	public function reservas()
	{
		return $this->hasMany(Reserva::class);
	}
}
