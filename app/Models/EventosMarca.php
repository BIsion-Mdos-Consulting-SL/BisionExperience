<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventosMarca
 * 
 * @property int $id
 * @property int $evento_id
 * @property int $marca_id
 *
 * @package App\Models
 */
class EventosMarca extends Model
{
	protected $table = 'eventos_marca';
	public $timestamps = false;

	protected $casts = [
		'evento_id' => 'int',
		'marca_id' => 'int'
	];

	protected $fillable = [
		'evento_id',
		'marca_id'
	];
}
