<label class="block group cursor-pointer relative">
    <input type="radio" name="metodo_pago" value="{{ $value }}" class="peer sr-only" {{ isset($checked) && $checked ? 'checked' : '' }}>
    
    <div class="p-5 rounded-2xl border border-zinc-700 bg-zinc-800/40 shadow-lg shadow-black/10 
                peer-checked:border-{{ $colorFrom ?? 'green-500' }} peer-checked:bg-zinc-800/80
                group-hover:border-{{ $colorFrom ?? 'green-500' }}/40 transition-all duration-300 relative overflow-hidden">
        
        <!-- Glow effect on check -->
        <div class="absolute inset-0 bg-gradient-to-r from-{{ $colorFrom ?? 'green-500' }}/10 to-transparent opacity-0 peer-checked:opacity-100 transition-opacity duration-500"></div>

        <div class="flex justify-between items-center relative z-10">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-zinc-800 to-zinc-900 border border-zinc-700 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300 peer-checked:from-{{ $colorFrom ?? 'green-500' }} peer-checked:to-{{ $colorTo ?? 'green-600' }} peer-checked:border-transparent">
                    <i class="fas {{ $icon }} text-xl text-zinc-400 peer-checked:text-white transition-colors duration-300"></i>
                </div>
                
                <div>
                    <p class="text-white text-lg font-bold group-hover:text-{{ $colorFrom ?? 'green-400' }} peer-checked:text-white transition-colors">
                        {{ $title }}
                    </p>
                    <p class="text-sm text-zinc-400 peer-checked:text-zinc-300">
                        {{ $desc }}
                    </p>
                </div>
            </div>

            <div class="w-6 h-6 rounded-full border-2 border-zinc-600 peer-checked:border-{{ $colorFrom ?? 'green-500' }} peer-checked:bg-{{ $colorFrom ?? 'green-500' }} flex items-center justify-center transition-all duration-300">
                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transform scale-50 peer-checked:scale-100 transition-all duration-300"></i>
            </div>
        </div>
    </div>
</label>
