<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        View::share('usuario', $request->attributes->get('usuario'));

        Blade::directive('canInsert', function ($expression) {
            return "<?php if (request()->attributes->get('permissions') && collect(request()->attributes->get('permissions'))->contains(fn(\$permiso) => \$permiso['NOMBRE_OBJETO'] === {$expression} && \$permiso['ESTADO_INSERCION'] === '1')) : ?>";
        });
        
        Blade::directive('endCanInsert', function () {
            return "<?php endif; ?>";
        });
    }
}