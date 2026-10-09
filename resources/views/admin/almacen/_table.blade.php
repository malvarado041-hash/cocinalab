@php $qs = request()->query(); @endphp
<div class="hidden md:block">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-[25%] lg:w-[20%] px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                <th class="hidden lg:table-cell w-[20%] px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="w-[25%] lg:w-[20%] px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="w-[25%] lg:w-[20%] px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="w-[25%] lg:w-[20%] px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider sticky-col">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($productos as $p)
                <tr class="hover:bg-gray-50 transition-colors almacen-row cursor-pointer" data-url="{{ route('admin.almacen.show', $p->id) }}">
                    <td class="px-3 py-4">
                        <p class="font-medium text-gray-800 truncate">{{ $p->nombre }}</p>
                    </td>
                    <td class="hidden lg:table-cell px-3 py-4">
                        <span class="inline-flex-cell inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            {{ $p->categoria_label }}
                        </span>
                    </td>
                    <td class="px-3 py-4">
                        <span class="font-semibold {{ $p->esBajoStock() ? 'text-red-600' : 'text-gray-800' }}">
                            {{ number_format($p->stock, 2) }}
                        </span>
                        <span class="text-xs text-gray-500">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->stock) }}</span>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-600">
                        @if ($p->precio_unitario !== null)
                            ${{ number_format($p->precio_unitario, 2) }}
                            <span class="text-xs text-gray-400 block">por {{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-3 py-4 text-center sticky-col">
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
                    <td colspan="5" class="px-6 py-16 text-center">
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

<div class="md:hidden divide-y divide-gray-100">
    @forelse ($productos as $p)
        <div class="almacen-row px-4 py-4 space-y-2 cursor-pointer hover:bg-gray-50 transition-colors" data-url="{{ route('admin.almacen.show', $p->id) }}">
            <div class="flex items-start justify-between gap-3">
                <p class="font-medium text-gray-800">{{ $p->nombre }}</p>
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 flex-shrink-0">
                    {{ $p->categoria_label }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-xs text-gray-500">Stock: </span>
                    <span class="font-semibold {{ $p->esBajoStock() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ number_format($p->stock, 2) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->stock) }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Precio: </span>
                    @if ($p->precio_unitario !== null)
                        <span class="text-gray-700">${{ number_format($p->precio_unitario, 2) }}</span>
                        <span class="text-xs text-gray-400">por {{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 pt-1">
                <a href="{{ route('admin.almacen.edit', array_merge(['almacen' => $p->id], $qs)) }}" class="btn-secondary text-sm px-3 py-1.5" title="Editar">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <form action="{{ route('admin.almacen.destroy', array_merge(['almacen' => $p->id], $qs)) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger text-sm px-3 py-1.5" title="Dar de baja"
                        onclick="return confirm('¿Dar de baja a {{ $p->nombre }}?')">
                        <i class="fas fa-trash mr-1"></i> Dar de baja
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-boxes text-3xl text-gray-400"></i>
                </div>
                <p class="text-lg font-medium text-gray-800">No hay productos registrados</p>
                <a href="{{ route('admin.almacen.create') }}" class="btn-primary text-sm px-4 py-2">Registrar el primero</a>
            </div>
        </div>
    @endforelse
</div>

<div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-4">
    <div class="text-sm text-gray-500">
        Mostrando {{ $productos->firstItem() ?? 0 }} a {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }} productos
    </div>
    <div class="ml-auto" id="almacen-pagination">{{ $productos->links() }}</div>
</div>
