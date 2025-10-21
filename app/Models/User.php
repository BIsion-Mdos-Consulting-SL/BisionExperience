<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\ClienteVerifyEmail;
use App\Notifications\CustomResetPasssword;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as DefaultVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $rol
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Reserva[] $reservas
 *
 * @package App\Models
 */
class User extends Authenticatable

{

	use Notifiable;

	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'rol',
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function reservas()
	{
		return $this->hasMany(Reserva::class);
	}

	/**Recogemos en una funcion el rol cliente para trabajar con eso. */
	public function isCliente()
	{
		return $this->rol === 'cliente';
	}

	/**Crearemos una funcion en donde mediante php artisan make:notification ClienteVerifyEmail verificaremos el email. */
	public function enviarEmailNotificacion()
	{
		if ($this->rol === 'cliente') {
			$this->notify(new ClienteVerifyEmail);
			return;
		}
		$this->notify(new DefaultVerifyEmail);
	}

	/**Metodo - funcion  para reseteo paswword  , envia el token: es recomendable  crear otros porque asi te lias menos.*/
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new CustomResetPasssword($token));
	}
}
