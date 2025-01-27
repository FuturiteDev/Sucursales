<?php

namespace Ongoing\Sucursales\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Ongoing\Inventarios\Entities\ProductosPendientesTraspaso;
/**
 * Class Sucursales.
 *
 * @package namespace App\Entities;
 */
class Sucursales extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
<<<<<<< HEAD
    protected $fillable = ['nombre', 'direccion', 'telefono', 'whatsapp', 'email', 'estatus'];
=======
    protected $fillable = ['nombre', 'direccion', 'estatus', 'matriz'];
>>>>>>> 7fdb29b (sucursales matriz agregada)


    public function productosPendientesTraspaso()
    {
        return $this->hasMany(ProductosPendientesTraspaso::class);
    }
}
