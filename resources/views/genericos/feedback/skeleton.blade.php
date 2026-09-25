<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @for($i = 0; $i < ($cuenta ?? 3); $i++)
        @if($tipo === 'card')
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="aspect-video skeleton-loader"></div>
                <div class="p-4 space-y-3">
                    <div class="h-4 w-3/4 skeleton-loader rounded"></div>
                    <div class="h-4 w-1/2 skeleton-loader rounded"></div>
                    <div class="h-4 w-full skeleton-loader rounded"></div>
                    <div class="h-4 w-2/3 skeleton-loader rounded"></div>
                    <div class="flex gap-2 pt-2">
                        <div class="h-6 w-20 skeleton-loader rounded-full"></div>
                        <div class="h-6 w-24 skeleton-loader rounded-full"></div>
                    </div>
                </div>
            </div>
        @elseif($tipo === 'receta')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="aspect-video skeleton-loader"></div>
                <div class="p-4 space-y-3">
                    <div class="flex gap-2">
                        <div class="h-5 w-20 skeleton-loader rounded"></div>
                        <div class="h-5 w-24 skeleton-loader rounded"></div>
                    </div>
                    <div class="h-6 w-3/4 skeleton-loader rounded"></div>
                    <div class="h-4 w-full skeleton-loader rounded"></div>
                    <div class="h-4 w-2/3 skeleton-loader rounded"></div>
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 skeleton-loader rounded-full"></div>
                            <div class="h-4 w-24 skeleton-loader rounded"></div>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="h-4 w-4 skeleton-loader rounded"></div>
                            <div class="h-4 w-16 skeleton-loader rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tipo === 'lista')
            <div class="flex gap-4 p-4 bg-white rounded-lg border border-gray-100">
                <div class="h-12 w-12 skeleton-loader rounded-lg flex-shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-5 w-1/3 skeleton-loader rounded"></div>
                    <div class="h-4 w-full skeleton-loader rounded"></div>
                    <div class="h-4 w-2/3 skeleton-loader rounded"></div>
                </div>
            </div>
        @elseif($tipo === 'tabla')
            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                @foreach($columnas ?? 4 as $col)
                                    <th class="px-4 py-3 text-left">
                                        <div class="h-4 w-20 skeleton-loader rounded"></div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for($j = 0; $j < ($filas ?? 5); $j++)
                                <tr class="border-t border-gray-100">
                                    @for($k = 0; $k < ($columnas ?? 4); $k++)
                                        <td class="px-4 py-3">
                                            <div class="h-4 w-24 skeleton-loader rounded"></div>
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($tipo === 'formulario')
            <div class="bg-white rounded-lg border border-gray-100 p-6 space-y-4">
                @for($j = 0; $j < ($campos ?? 4); $j++)
                    <div class="space-y-1.5">
                        <div class="h-4 w-1/4 skeleton-loader rounded"></div>
                        <div class="h-10 w-full skeleton-loader rounded"></div>
                    </div>
                @endfor
                <div class="h-10 w-32 skeleton-loader rounded-lg"></div>
            </div>
        @elseif($tipo === 'perfil')
            <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div class="h-20 w-20 skeleton-loader rounded-full"></div>
                    <div class="space-y-2">
                        <div class="h-6 w-40 skeleton-loader rounded"></div>
                        <div class="h-4 w-32 skeleton-loader rounded"></div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @for($j = 0; $j < 3; $j++)
                        <div class="text-center">
                            <div class="h-8 w-16 skeleton-loader rounded mx-auto"></div>
                            <div class="h-4 w-20 skeleton-loader rounded mx-auto mt-1"></div>
                        </div>
                    @endfor
                </div>
            </div>
        @else
            <div class="h-{{ $alto ?? 12 }} w-full skeleton-loader rounded"></div>
        @endif
    @endfor
</div>

<style>
.skeleton-loader {
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s ease-in-out infinite;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.dark .skeleton-loader {
    background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
    background-size: 200% 100%;
}
</style>