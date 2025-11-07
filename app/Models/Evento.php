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
		'imagen',
		'public_id'
	];

	/**
	 * La función "invitados" define una relación de muchos a muchos entre el modelo actual y el
	 * modelo "Conductor" con atributos adicionales de tabla pivote.
	 * 
	 * @return La función `invitados` devuelve una relación de muchos a muchos entre el modelo actual
	 * y el modelo `Conductor`. Especifica la tabla intermedia `evento_conductor` con las claves externas
	 * `evento_id` y `conductor_id`. Además, incluye las columnas pivote especificadas, como
	 * `cif`, `nombre`, `apellido`, `email` y otras, en la tabla pivote.
	 */

	public function invitados()
	{
		return $this->belongsToMany(Conductor::class, 'evento_conductor', 'evento_id', 'conductor_id')
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
	 * La función `booted` en PHP asigna un `public_id` único a un evento si está vacío, utilizando el evento `creating` de Laravel.
	 */
	protected static function booted()
	{
		static::creating(function ($evento) {
			if (empty($evento->public_id)) {
				$evento->public_id = (string) \Illuminate\Support\Str::uuid();
			}
		});
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

	/**
	* La función `getRouteKeyName()` en PHP devuelve el nombre de la clave de ruta 'public_id'.
	* 
	* @return El método `getRouteKeyName()` devuelve la cadena `'public_id'`. Este método se utiliza en
	* Laravel para especificar el atributo que se debe usar al recuperar un modelo por su clave de ruta. En
	* este caso, la clave de ruta para el modelo será el atributo `public_id`.
	*/
	public function getRouteKeyName()
	{
		return 'public_id';
	}
}
