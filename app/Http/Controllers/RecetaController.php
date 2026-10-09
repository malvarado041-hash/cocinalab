<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecetaController extends Controller
{
    private array $excluir = ['agua', 'aceite', 'aceite de oliva', 'sal', 'pimienta', 'azucar', 'canela', 'cebolla blanca', 'cebolla morada', 'cilantro', 'diente ajo', 'diente de ajo', 'ajo en polvo', 'chile serrano', 'chiles serranos', 'pan de caja', 'pan integral', 'tomate rojo', 'tomate verde', 'salsa al gusto', 'aderezo cesar', 'oregano', 'soja', 'arvejas', 'ciabatta', 'jugo de limon', 'vinagre balsamico', 'mayonesa'];

    // Ingredientes por tipo
    public function index()
    {
        $tipos = ['Desayuno', 'Comida', 'Cena', 'Postre'];
        $ingredientesPorTipo = [];
        foreach ($tipos as $tipo) {
            $ings = $this->ingredientesPorTipo($tipo);
            if ($ings->isNotEmpty()) {
                $ingredientesPorTipo[$tipo] = $ings;
            }
        }
        return view('recetas.index', compact('ingredientesPorTipo'));
    }

    private function ingredientesPorTipo(string $tipo)
    {
        return Ingrediente::select('ingredientes.Nombre')
            ->distinct()
            ->join('receta_ingrediente as ri', 'ingredientes.id', '=', 'ri.ingrediente_id')
            ->join('recetas as r', 'r.id', '=', 'ri.receta_id')
            ->where('r.TipoC', $tipo)
            ->whereNotIn('ingredientes.Nombre', $this->excluir)
            ->orderBy('ingredientes.Nombre')
            ->pluck('Nombre');
    }

    // Filtrar
    public function filtrar(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'ingrediente' => 'required|string',
        ]);

        $recetas = Receta::select('recetas.id', 'recetas.Nombre')
            ->distinct()
            ->join('receta_ingrediente as ri', 'recetas.id', '=', 'ri.receta_id')
            ->join('ingredientes as i', 'ri.ingrediente_id', '=', 'i.id')
            ->where('recetas.TipoC', $data['tipo'])
            ->where('i.Nombre', $data['ingrediente'])
            ->orderBy('recetas.Nombre')
            ->get();

        return view('recetas.filtrar', [
            'tipo' => $data['tipo'],
            'ingrediente' => $data['ingrediente'],
            'recetas' => $recetas,
        ]);
    }

    // Detalle / buscar
    public function show(Request $request)
    {
        $receta = null;
        if ($request->filled('id') && is_numeric($request->query('id'))) {
            $receta = Receta::select('Nombre', 'Procedimiento')->find((int) $request->query('id'));
        } elseif ($request->filled('buscar') && trim($request->query('buscar')) !== '') {
            $buscar = trim($request->query('buscar'));
            $receta = Receta::select('Nombre', 'Procedimiento')->where('Nombre', 'like', "%{$buscar}%")->first();
        } else {
            abort(400, 'ID de receta no proporcionado.');
        }

        if (! $receta) {
            abort(404, 'No se encontró la receta.');
        }

        return view('recetas.show', compact('receta'));
    }
}
