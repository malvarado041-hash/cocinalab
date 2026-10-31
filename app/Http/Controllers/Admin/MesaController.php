<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Mesa,Comanda,User};
use Illuminate\Http\Request;
class MesaController extends Controller {
 public function index() {
  return view('admin.mesas', ['mesas'=>Mesa::where('activa',true)->orderBy('nombre')->get(),'cuentas'=>Comanda::with('mesero')->whereNull('cerrada_at')->where('estado','!=','cancelada')->get(),'meseros'=>User::where('status','activo')->whereIn('role',['mesero','capitan','admin'])->get()]);
 }
 public function store(Request $r) {
  abort_unless(auth()->user()->isPrivileged() || auth()->user()->hasRole('capitan'),403);
  Mesa::create($r->validate(['nombre'=>'required|string|max:100|unique:mesas,nombre','capacidad'=>'required|integer|min:1|max:100']));
  return back()->with('success','Mesa creada.');
 }
 public function asignar(Request $r, Comanda $comanda) {
  abort_unless(auth()->user()->isPrivileged() || auth()->user()->hasRole('capitan'),403);
  $d=$r->validate(['mesero_id'=>'required|exists:users,id']);
  $u=User::findOrFail($d['mesero_id']); abort_unless($u->isActive() && in_array($u->role,['mesero','capitan','admin']),422);
  \Illuminate\Support\Facades\DB::transaction(function() use($comanda,$d) {
   $c=Comanda::lockForUpdate()->findOrFail($comanda->id); abort_if($c->cerrada_at || $c->estado==='cancelada',422);
   $c->update($d);
   \App\Models\EventoOperacion::create(['user_id'=>auth()->id(),'comanda_id'=>$c->id,'accion'=>'mesero_asignado','detalle'=>(string)$d['mesero_id']]);
  });
  return back()->with('success','Mesero asignado.');
 }
}
