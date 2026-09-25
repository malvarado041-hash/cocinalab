<div {{ $attributes->merge(['class' => 'inline-flex shrink-0']) }}>
    @php
        $tamanos = [
            'xs' => 'w-6 h-6 text-xs',
            'sm' => 'w-8 h-8 text-sm',
            'md' => 'w-10 h-10 text-base',
            'lg' => 'w-12 h-12 text-lg',
            'xl' => 'w-16 h-16 text-xl',
            '2xl' => 'w-20 h-20 text-2xl',
        ];
        
        $colores = [
            'gray' => 'bg-gray-100 text-gray-600',
            'red' => 'bg-red-100 text-red-600',
            'orange' => 'bg-orange-100 text-orange-600',
            'amber' => 'bg-amber-100 text-amber-600',
            'yellow' => 'bg-yellow-100 text-yellow-600',
            'green' => 'bg-green-100 text-green-600',
            'emerald' => 'bg-emerald-100 text-emerald-600',
            'teal' => 'bg-teal-100 text-teal-600',
            'blue' => 'bg-blue-100 text-blue-600',
            'indigo' => 'bg-indigo-100 text-indigo-600',
            'purple' => 'bg-purple-100 text-purple-600',
            'pink' => 'bg-pink-100 text-pink-600',
        ];
    @endphp

    @if($imagen)
        <img src="{{ $imagen }}" 
             alt="{{ $alt ?? $nombre ?? 'Avatar' }}"
             class="{{ $tamanos[$tamano ?? 'md'] }} rounded-full object-cover ring-2 ring-white
                    {{ $borde ? 'ring-' . $borde . '-200' : 'ring-gray-200' }}"
             {{ $attributes->except('class') }}>
    @else
        <div class="{{ $tamanos[$tamano ?? 'md'] }} rounded-full flex items-center justify-center font-medium select-none
                       {{ $colores[$color ?? 'gray'] ?? 'bg-gray-100 text-gray-600' }}
                       {{ $borde ? 'ring-2 ring-white ring-' . $borde . '-200' : 'ring-2 ring-white ring-gray-200' }}">
            @if($icono)
                <x-genericos.icon :nombre="$icono" :tamano="$tamano" clase="text-current" />
            @elseif($iniciales)
                {{ $iniciales }}
            @elseif($nombre)
                {{ Str::upper(collect(explode(' ', $nombre))->map(fn($p) => $p[0])->take(2)->implode('')) }}
            @else
                <x-genericos.icon nombre="user" :tamano="$tamano" clase="text-current" />
            @endif
        </div>
    @endif

    @if($estado)
        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 ring-white
                       {{ $estado === 'online' ? 'bg-green-500' : '' }}
                       {{ $estado === 'offline' ? 'bg-gray-400' : '' }}
                       {{ $estado === 'busy' ? 'bg-red-500' : '' }}
                       {{ $estado === 'away' ? 'bg-yellow-500' : '' }}"
              aria-label="{{ ucfirst($estado) }}"></span>
    @endif
</div>

@if($grupo)
    <div class="flex -space-x-2" role="group" aria-label="{{ $grupoLabel ?? 'Grupo de avatares' }}">
        @foreach($avatares ?? [] as $index => $avatar)
            <x-genericos.avatar 
                :imagen="$avatar['imagen'] ?? null"
                :nombre="$avatar['nombre'] ?? null"
                :iniciales="$avatar['iniciales'] ?? null"
                :tamano="$tamano"
                :estado="$avatar['estado'] ?? null"
                class="z-{{ count($avatares) - $index }}"
                {{ $index >= ($maxVisibles ?? 4) ? 'hidden sm:inline-flex' : '' }} />
        @endforeach
        
        @if(count($avatares ?? []) > ($maxVisibles ?? 4))
            <div class="{{ $tamanos[$tamano ?? 'md'] }} rounded-full flex items-center justify-center font-medium text-xs bg-gray-100 text-gray-600 ring-2 ring-white"
                 aria-label="{{ count($avatares) - ($maxVisibles ?? 4) }} más">
                +{{ count($avatares) - ($maxVisibles ?? 4) }}
            </div>
        @endif
    </div>
@endif