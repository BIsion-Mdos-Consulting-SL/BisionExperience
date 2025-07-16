<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parada
 * 
 * @property int $id
 * @property string $nombre_vehiculo
 * @property string $conductor
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_fin
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
		'hora_fin'
	];
}
