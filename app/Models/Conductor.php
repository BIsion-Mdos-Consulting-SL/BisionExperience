<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conductor
 * 
 * @property int $id
 * @property string|null $cif
 * @property string|null $nombre
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
 * @property Carbon|null $carnet_caducidad
 *
 * @package App\Models
 */
class Conductor extends Model
{
	protected $table = 'conductor';
	public $timestamps = false;

	protected $casts = [
		'asiste' => 'int',
		'proteccion_datos' => 'bool',
		'carnet_caducidad' => 'date'
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
		'etiqueta_2',
		'kam',
		'asiste',
		'dni',
		'proteccion_datos',
		'carnet_caducidad'
	];

	/**

	 * La función "eventos" define una relación de muchos a muchos entre un modelo "Conductor" y un

	 * modelo "Evento" en PHP, con campos adicionales en la tabla pivote especificados.

	 * 
	 * @return El fragmento de código define un método llamado `eventos` que establece una relación de muchos a muchos

	 * entre el modelo actual y el modelo `Evento` utilizando la tabla pivote `evento_conductor`.

	 * método `withPivot`. Estas columnas pivote adicionales incluyen 'cif', 'nombre', 'apellido', 'email',

	 * 'telefono', '

	 */
	public function eventos()
	{
		return $this->belongsToMany(Evento::class, 'evento_conductor', 'conductor_id', 'evento_id')
			->withPivot([
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
				'carnet_caducidad',
				'confirmado',
				'token',
				'etiqueta_2'
			]);
	}

	/**
	 * La función conductores() establece una relación de muchos a muchos entre el modelo actual y

	 * el modelo Conductor utilizando la tabla pivote evento_conductor.

	 * 
	 * @return La función `conductores` devuelve una relación de muchos a muchos entre el modelo actual

	 * y el modelo `Conductor`. Especifica la tabla pivote `evento_conductor` y las claves externas `evento_id` y `conductor_id` para la relación.
	 */
	public function conductores()
	{
		return $this->belongsToMany(Conductor::class, 'evento_conductor', 'evento_id', 'conductor_id');
	}
}
