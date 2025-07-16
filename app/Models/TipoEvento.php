<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEvento
 * 
 * @property int $id
 * @property string $nombre
 *
 * @package App\Models
 */
class TipoEvento extends Model
{
	protected $table = 'tipo_evento';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];
}
