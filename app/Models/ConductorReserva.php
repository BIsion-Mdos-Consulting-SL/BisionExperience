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
 * @property bool $confirmado
 * @property string|null $token
 *
 * @package App\Models
 */
class EventoConductor extends Model
{
	protected $table = 'conductor_reserva';
	public $timestamps = false;

	protected $casts = [
		'evento_id' => 'int',
		'conductor_id' => 'int',
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'evento_id',
		'conductor_id',
		'token'
	];

	public function invitados()
	{
		return $this->hasMany(Conductor::class);
	}
}
