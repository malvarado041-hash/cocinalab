<?php
namespace App\Services;
use App\Models\{AlmacenProducto, ComandaItem, MovimientoInventario};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class ConsumoInventario {
 /** Se llama dentro de la transacción que bloquea la cuenta y el platillo. */
 public function descontar(ComandaItem $item): void {
  if ($item->consumido_at) return;
  $receta = $item->receta;
  $costo = $receta?->costoEstimado();
  if (!$costo || !$costo['completo']) throw ValidationException::withMessages(['inventario' => 'Completa los vínculos, cantidades y unidades de la receta antes de marcarla lista.']);
  $necesarios = [];
  foreach ($receta->ingredientes()->with('almacenProducto')->get() as $ing) {
   $p = $ing->almacenProducto;
   $cantidad = $ing->pivot->cantidad_num ?? \App\Models\Receta::parseCantidad($ing->pivot->cantidad);
   $factor = \App\Models\Receta::factorConversion($ing->pivot->unidad, $p?->unidad);
   if (!$p || !$factor || !$cantidad) throw ValidationException::withMessages(['inventario' => 'La receta tiene ingredientes incompletos.']);
   $necesarios[$p->id] = ($necesarios[$p->id] ?? 0) + $cantidad * $factor * $item->cantidad / max(1, $receta->porciones);
  }
  ksort($necesarios);
  foreach ($necesarios as $id => $cantidad) {
   $p = AlmacenProducto::lockForUpdate()->findOrFail($id);
   $cantidad = round($cantidad,6);
   if ($cantidad <= 0 || (float)$p->stock + 0.0000001 < $cantidad) throw ValidationException::withMessages(['inventario' => "Stock insuficiente de {$p->nombre}. Necesitas {$cantidad} {$p->unidad}."]);
   $p->stock = round((float)$p->stock - $cantidad,6); $p->save();
   MovimientoInventario::create(['almacen_producto_id'=>$id,'comanda_item_id'=>$item->id,'user_id'=>Auth::id(),'tipo'=>'consumo','cantidad'=>-$cantidad,'stock_resultante'=>$p->stock,'costo_unitario'=>$p->precio_unitario,'motivo'=>'Preparación de platillo listo']);
  }
  $item->consumido_at = now();
 }
}
