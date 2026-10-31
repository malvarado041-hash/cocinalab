<?php
namespace App\Services;
use App\Models\{LoteInventario,MovimientoInventario,AlmacenProducto};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class LotesInventario {
 /** Producto ya bloqueado; conserva el vínculo de cada salida con sus lotes. */
 public function retirar(AlmacenProducto $p, float $cantidad, MovimientoInventario $m, bool $permitirVencidos=false): void {
  $lotes=LoteInventario::where('almacen_producto_id',$p->id)->where('disponible','>',0)->orderByRaw('caducidad IS NULL')->orderBy('caducidad')->orderBy('id')->lockForUpdate()->get();
  $sinLote=max(0,(float)$p->stock-$lotes->sum('disponible')); $restante=$cantidad;
  foreach($lotes as $l) {
   if(!$permitirVencidos && $l->caducidad && $l->caducidad->lt(today())) continue;
   $tomar=min($restante,(float)$l->disponible); if($tomar<=0) break;
   $l->disponible=round($l->disponible-$tomar,6); $l->save();
   DB::table('movimiento_lotes')->insert(['movimiento_id'=>$m->id,'lote_id'=>$l->id,'cantidad'=>-$tomar]);
   $restante=round($restante-$tomar,6);
  }
  if($restante>$sinLote+0.0000001) throw ValidationException::withMessages(['inventario'=>'No hay suficiente stock utilizable: existen lotes vencidos. Registra su merma antes de continuar.']);
 }
}
