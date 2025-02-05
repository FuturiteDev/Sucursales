<?php

namespace Ongoing\Sucursales\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

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
    protected $fillable = ['nombre', 'direccion', 'telefono', 'whatsapp', 'email', 'estatus', 'matriz'];

}
