@php $qs = request()->query(); @endphp
<div>
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-[30%] px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                <th class="hidden lg:table-cell w-[12%] px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="w-[30%] md:w-[24%] lg:w-[17%] px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="hidden md:table-cell md:w-[12%] lg:w-[10%] px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unidad</th>
                <th class="w-[25%] md:w-[19%] lg:w-[16%] px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="w-[15%] px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider" sticky-col">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($productos as $p)
                <tr class="hover:bg-gray-50 transition-colors almacen-row cursor-pointer" data-url="{{ route('admin.almacen.show', $p->id) }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $p->esBajoStock() ? 'bg-red-100' : 'bg-green-100' }}">
                                <i class="fas fa-box {{ $p->esBajoStock() ? 'text-red-600' : 'text-green-600' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $p->nombre }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ str_replace('_', ' ', $p->subcategoria) }}@if($p->proveedor) · {{ $p->proveedor }}@endif</p>
                            </div>
                        </div>
                    </td>
                    <td class="hidden lg:table-cell px-6 py-4">
                        <span class="inline-flex-cell inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            {{ $p->categoria_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold {{ $p->esBajoStock() ? 'text-red-600' : 'text-gray-800' }}">
                            {{ number_format($p->stock, 2) }} {{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->stock) }}
                        </span>
                        <span class="text-xs text-gray-400 block">mín {{ number_format($p->stock_minimo, 2) }} {{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->stock_minimo) }}</span>
                        @if ($p->esBajoStock())
                            <span class="text-xs text-red-700 bg-red-100 px-2 py-0.5 rounded-full">Bajo</span>
                        @endif
                    </td>
                    <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if ($p->precio_unitario !== null)
                            ${{ number_format($p->precio_unitario, 2) }}
                            <span class="text-xs text-gray-400 block">por {{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center sticky-col">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.almacen.edit', array_merge(['almacen' => $p->id], $qs)) }}" class="hidden min-[400px]:inline-flex btn-secondary text-sm px-3 py-1.5" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.almacen.destroy', array_merge(['almacen' => $p->id], $qs)) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-sm px-3 py-1.5" title="Dar de baja"
                                    onclick="return confirm('¿Dar de baja a {{ $p->nombre }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-boxes text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-800">No hay productos registrados</p>
                            <a href="{{ route('admin.almacen.create') }}" class="btn-primary text-sm px-4 py-2">Registrar el primero</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-4">
    <div class="text-sm text-gray-500">
        Mostrando {{ $productos->firstItem() ?? 0 }} a {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }} productos
    </div>
    <div class="ml-auto" id="almacen-pagination">{{ $productos->links() }}</div>
</div>
