<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Marca
 * 
 * @property int $id
 * @property string $nombre
 *
 * @package App\Models
 */
class Marca extends Model
{
	protected $table = 'marcas';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function eventos()
	{
		return $this->belongsToMany(Evento::class, 'eventos_marca', 'marca_id', 'evento_id');
	}
}
