<?php

namespace App\Providers;

use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services , esto se encarga de inyectar $banners , sin importar si vienes del login u otra vista(MUY IMPORTANTE).
     */
    public function boot(): void
    {
        /**Esta clase la de RedirectResponse da la respuesat en el redirect(), back() hay que tenerlo muchisimo en cuenta , con macro inyevtamos un metodo en este caso su nombre. Este armara un array con los campos pasados por parametro , que luego guarda en la sesion usando el with. */
        RedirectResponse::macro('toast', function (string $icon, string $title, array $extra = []) {
            /** @var RedirectResponse $this */
            $payload = array_merge([
                'icon'  => $icon,      // 'success' | 'error' | 'info' | 'warning' | 'question'
                'title' => $title,
            ], $extra);

            return $this->with('toast', $payload);
        });
    }
}
