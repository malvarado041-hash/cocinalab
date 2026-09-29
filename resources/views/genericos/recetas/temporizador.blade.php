<div class="{{ $attributes->class ?? '' }}" {{ $attributes->except('class') }} 
     x-data="{
         tiempo: {{ $segundos ?? 0 }},
         intervalo: null,
         ejecutando: false,
         inicial: {{ $segundos ?? 0 }}
     }"
     x-init="
         if ({{ $autoStart ?? false }}) {
             iniciar();
         }
     ">
    
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $titulo ?? 'Temporizador' }}
            </h3>
            
            @if($editable)
                <button @click="tiempo = prompt('Segundos:', tiempo) || tiempo; $wire.set('{{ $wireModelTiempo }}', tiempo)"
                        class="p-1 rounded-lg bg-white/20 hover:bg-white/30 transition-colors"
                        aria-label="Editar tiempo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Display del tiempo --}}
        <div class="font-mono text-4xl font-bold text-center tabular-nums" 
             :class="{ 'animate-pulse': ejecutando && tiempo < 10 }"
             x-text="formatearTiempo(tiempo)"></div>

        {{-- Barra de progreso --}}
        <div class="mt-4 h-2 bg-white/20 rounded-full overflow-hidden">
            <div class="h-full bg-white transition-all duration-300" 
                 :style="'width: ' + (inicial > 0 ? (100 - (tiempo / inicial * 100)) : 0) + '%'"></div>
        </div>

        {{-- Controles --}}
        <div class="mt-6 flex items-center justify-center gap-3">
            <button @click="reiniciar()"
                    class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-sm font-medium transition-colors"
                    :disabled="tiempo == inicial"
                    aria-label="Reiniciar">
                Reiniciar
            </button>

            <button @click="ejecutando ? pausar() : iniciar()"
                    class="px-6 py-2 rounded-lg bg-white text-orange-600 font-semibold hover:bg-gray-100 transition-colors"
                    :aria-label="ejecutando ? 'Pausar' : 'Iniciar'"
                    x-text="ejecutando ? 'Pausar' : 'Iniciar'"></button>

            <button @click="reiniciar()"
                    class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-sm font-medium transition-colors"
                    aria-label="Resetear">
                Reset
            </button>
        </div>

        {{-- Presets --}}
        @if($presets)
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                @foreach($presets as $preset)
                    <button @click="tiempo = {{ $preset['segundos'] }}; $wire.set('{{ $wireModelTiempo }}', {{ $preset['segundos'] }})"
                            class="px-3 py-1 text-xs rounded-full bg-white/20 hover:bg-white/30 transition-colors"
                            type="button">
                        {{ $preset['label'] }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function formatearTiempo(segundos) {
            const h = Math.floor(segundos / 3600);
            const m = Math.floor((segundos % 3600) / 60);
            const s = segundos % 60;
            return [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');
        }

        function iniciar() {
            if (this.ejecutando || this.tiempo <= 0) return;
            this.ejecutando = true;
            this.intervalo = setInterval(() => {
                if (this.tiempo > 0) {
                    this.tiempo--;
                    this.$wire.set('{{ $wireModelTiempo }}', this.tiempo);
                } else {
                    this.pausar();
                    this.alarma();
                }
            }, 1000);
        }

        function pausar() {
            this.ejecutando = false;
            clearInterval(this.intervalo);
        }

        function reiniciar() {
            this.pausar();
            this.tiempo = this.inicial;
            this.$wire.set('{{ $wireModelTiempo }}', this.tiempo);
        }

        function alarma() {
            // Notificación visual
            this.$dispatch('temporizador-finalizado');
            
            // Sonido (opcional)
            if (typeof Audio !== 'undefined') {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWTQ==');
                audio.play().catch(() => {});
            }
        }
    </script>
</div>