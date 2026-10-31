<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LoteInventario extends Model {
 protected $table='lotes_inventario'; protected $guarded=[]; protected $casts=['caducidad'=>'date'];
 public function producto() { return $this->belongsTo(AlmacenProducto::class,'almacen_producto_id')->withTrashed(); }
}
