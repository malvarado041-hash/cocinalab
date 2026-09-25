<div x-data="{ 
    toasts: @entangle($wire.toasts ?? []),
    mostrar(toast) {
        this.toasts.push({ ...toast, id: Date.now() + Math.random() });
        if (toast.duration !== 0) {
            setTimeout(() => this.ocultar(toast.id), toast.duration ?? 5000);
        }
    },
    ocultar(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
 }" 
 x-init="
    @this.on('toast', (toast) => this.mostrar(toast));
    @this.on('toast:success', (msg) => this.mostrar({ tipo: 'success', mensaje: msg }));
    @this.on('toast:error', (msg) => this.mostrar({ tipo: 'error', mensaje: msg }));
    @this.on('toast:warning', (msg) => this.mostrar({ tipo: 'warning', mensaje: msg }));
    @this.on('toast:info', (msg) => this.mostrar({ tipo: 'info', mensaje: msg }));
 "
 class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-96 max-w-full"
 {{ $attributes }}>
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform opacity-0 translate-x-full"
             x-transition:enter-end="transform opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform opacity-100 translate-x-0"
             x-transition:leave-end="transform opacity-0 translate-x-full"
             class="flex items-start gap-3 p-4 rounded-lg shadow-lg border animate-slide-in
                    {{ toast.tipo === 'success' ? 'bg-green-50 border-green-200 text-green-800' : '' }}
                    {{ toast.tipo === 'error' ? 'bg-red-50 border-red-200 text-red-800' : '' }}
                    {{ toast.tipo === 'warning' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : '' }}
                    {{ toast.tipo === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800' : '' }}">
            
            <div class="flex-shrink-0 mt-0.5">
                @if(toast.tipo === 'success')
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @elseif(toast.tipo === 'error')
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                @elseif(toast.tipo === 'warning')
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                @if(toast.titulo)
                    <p class="font-medium">{{ toast.titulo }}</p>
                @endif
                <p class="text-sm {{ toast.titulo ? 'mt-0.5' : '' }}">{{ toast.mensaje }}</p>
                
                @if(toast.accion)
                    <button x-on:click="toast.accion.handler(); ocultar(toast.id)"
                            class="mt-2 text-sm font-medium underline hover:no-underline focus:outline-none focus:ring-2 focus:ring-offset-2
                                   {{ toast.tipo === 'success' ? 'focus:ring-green-500' : '' }}
                                   {{ toast.tipo === 'error' ? 'focus:ring-red-500' : '' }}
                                   {{ toast.tipo === 'warning' ? 'focus:ring-yellow-500' : '' }}
                                   {{ toast.tipo === 'info' ? 'focus:ring-blue-500' : '' }}">
                        {{ toast.accion.texto }}
                    </button>
                @endif
            </div>

            <button @click="ocultar(toast.id)"
                    class="flex-shrink-0 p-1 rounded hover:bg-black/10 transition-colors"
                    aria-label="Cerrar">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<style>
@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.3s ease-out; }
</style>