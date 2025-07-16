<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventoConductor
 * 
 * @property int $id
 * @property int $evento_id
 * @property int $conductor_id
 *
 * @package App\Models
 */
class EventoConductor extends Model
{
	protected $table = 'evento_conductor';
	public $timestamps = false;

	protected $casts = [
		'evento_id' => 'int',
		'conductor_id' => 'int'
	];

	protected $fillable = [
		'evento_id',
		'conductor_id'
	];

	public function invitados()
	{
		return $this->hasMany(Conductor::class);
	}
}
