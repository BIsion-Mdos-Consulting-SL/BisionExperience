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

	protected $casts = [
		'hora_inicio' => 'datetime',
		'hora_fin' => 'datetime'
	];

	protected $fillable = [
		'nombre_vehiculo',
		'conductor',
		'hora_inicio',
		'hora_fin',
		'nombre'
	];

	public function reservas()
	{
		return $this->hasMany(Reserva::class);
	}
}
