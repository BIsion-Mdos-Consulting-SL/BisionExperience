<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reserva
 * 
 * @property int $id
 * @property int $user_id
 * @property int $coche_id
 * @property int $parada_id
 * @property string $tipo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Coch $coch
 * @property Parada $parada
 * @property User $user
 *
 * @package App\Models
 */
class Reserva extends Model
{
	protected $table = 'reservas';

	protected $casts = [
		'user_id' => 'int',
		'coche_id' => 'int',
		'parada_id' => 'int',
		'evento_id' => 'int',
		'hora_inicio' => 'string',
		'hora_fin' => 'string'
	];

	protected $fillable = [
		'user_id',
		'coche_id',
		'parada_id',
		'evento_id',
		'tipo'
	];

	public function coch()
	{
		return $this->belongsTo(Coch::class, 'coche_id');
	}

	public function parada()
	{
		return $this->belongsTo(Parada::class , 'parada_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class , 'user_id');
	}

	public function evento(){
		return $this->belongsTo(Evento::class , 'evento_id');
	}
}
