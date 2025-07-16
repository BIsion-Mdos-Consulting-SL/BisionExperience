<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conductor
 * 
 * @property int $id
 * @property string|null $cif
 * @property string $nombre
 * @property string|null $apellido
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $empresa
 * @property string|null $vehiculo_prop
 * @property string|null $vehiculo_emp
 * @property string|null $intolerancia
 * @property string|null $preferencia
 * @property string|null $carnet
 * @property string|null $etiqueta
 * @property string|null $kam
 * @property int|null $asiste
 * @property string|null $dni
 * @property bool $proteccion_datos
 *
 * @package App\Models
 */
class Conductor extends Model
{
	protected $table = 'conductor';
	public $timestamps = false;

	protected $casts = [
		'asiste' => 'int',
		'proteccion_datos' => 'bool'
	];

	protected $fillable = [
		'cif',
		'nombre',
		'apellido',
		'email',
		'telefono',
		'empresa',
		'vehiculo_prop',
		'vehiculo_emp',
		'intolerancia',
		'preferencia',
		'carnet',
		'etiqueta',
		'kam',
		'asiste',
		'dni',
		'proteccion_datos',
		'carnet_caducidad'
	];

	public function eventos()
	{
		return $this->belongsToMany(Evento::class, 'evento_conductor', 'conductor_id', 'evento_id');
	}
}
