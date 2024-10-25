<?php

namespace Ongoing\Sucursales\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ongoing\Sucursales\Repositories\SucursalesRepository;
use Ongoing\Sucursales\Entities\Sucursales;
use App\Validators\SucursalesValidator;


/**
 * Class SucursalesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SucursalesRepositoryEloquent extends BaseRepository implements SucursalesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sucursales::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
