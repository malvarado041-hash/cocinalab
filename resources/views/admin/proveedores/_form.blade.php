@php
    $isEdit = isset($proveedor);
    $old = fn($k, $d = '') => old($k, $isEdit ? $proveedor->$k : $d);
@endphp

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" role="alert">
        <p class="font-semibold mb-1">Revisa los campos:</p>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
        <input type="text" name="nombre" value="{{ $old('nombre') }}" required maxlength="200"
            placeholder="Ej. Central de abastos"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
        <input type="text" name="telefono" value="{{ $old('telefono') }}" maxlength="50"
            placeholder="Ej. 55 1234 5678"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
        <input type="email" name="email" value="{{ $old('email') }}" maxlength="200"
            placeholder="Ej. contacto@proveedor.com"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
        <input type="text" name="direccion" value="{{ $old('direccion') }}" maxlength="255"
            placeholder="Ej. Av. Principal 123, Ciudad"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
</div>
