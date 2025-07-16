<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

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
		'evento_id'
	];

	public function evento()
	{
		return $this->belongsTo(Evento::class);
	}
}
