<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coche
 * 
 * @property int $matricula
 * @property string $modelo
 * @property string $marca
 * @property string $nombre_comercial
 * @property Carbon $fecha
 * @property Carbon|null $hora_entrada
 * @property Carbon|null $hora_salida
 *
 * @package App\Models
 */
class Coche extends Model
{
	protected $table = 'coche';
	protected $primaryKey = 'matricula';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'matricula' => 'int',
		'fecha' => 'datetime',
		'hora_entrada' => 'datetime',
		'hora_salida' => 'datetime'
	];

	protected $fillable = [
		'modelo',
		'marca',
		'nombre_comercial',
		'fecha',
		'hora_entrada',
		'hora_salida'
	];
}
