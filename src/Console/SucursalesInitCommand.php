<?php

namespace Ongoing\Sucursales\Console;

use Illuminate\Console\Command;
use App\Repositories\NavegacionRepositoryEloquent;

class SucursalesInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sucursales:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra las rutas en la navegacion del sitio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(NavegacionRepositoryEloquent $nav_repo)
    {

        $nav_gpo = $nav_repo->firstOrCreate([
            'descripcion' => 'Sucursales', 
            'url' => '/sucursales', 
            'padre_id' => 0, 
            'permisos' => []
        ]);

        $this->info("Proceso finalizado");

        return 0;
    }
}
