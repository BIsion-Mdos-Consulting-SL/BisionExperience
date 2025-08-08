<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coch
 * 
 * @property int $id
 * @property string|null $marca
 * @property string|null $modelo
 * @property string|null $version
 * @property string|null $matricula
 * @property string|null $kam
 * @property bool $asiste
 * @property int|null $evento_id
 * 
 * @property Collection|Reserva[] $reservas
 *
 * @package App\Models
 */
class Coch extends Model
{
	protected $table = 'coches';
	public $timestamps = false;

	protected $casts = [
		'asiste' => 'bool',
		'evento_id' => 'int'
	];

	protected $fillable = [
		'marca',
		'modelo',
		'version',
		'matricula',
		'kam',
		'asiste',
		'evento_id',
		'seguro',
		'documento_seguro',
		'foto_vehiculo'
	];

	public function reservas()
	{
		return $this->hasMany(Reserva::class, 'coche_id');
	}

	public function evento()
	{
		return $this->belongsTo(Evento::class, 'evento_id');
	}
}
