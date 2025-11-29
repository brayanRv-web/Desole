<label class="relative flex cursor-pointer rounded-2xl border border-zinc-600 bg-zinc-700/30 p-5 shadow-sm hover:bg-zinc-700/50 transition-all duration-300 group">
    <input type="radio" name="metodo_pago" value="{{ $value }}" class="peer sr-only" {{ isset($checked) && $checked ? 'checked' : '' }}>
    
    <div class="flex w-full items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 text-zinc-400 peer-checked:text-green-500 transition-colors">
                <i class="fas {{ $icon }} text-3xl"></i>
            </div>
            <div>
                <span class="block text-lg font-semibold text-white group-hover:text-green-400 transition-colors">
                    {{ $title }}
                </span>
                <span class="block text-sm text-zinc-400 mt-0.5">
                    {{ $text }}
                </span>
            </div>
        </div>

        <div class="h-6 w-6 rounded-full border-2 border-zinc-500 peer-checked:border-green-500 peer-checked:bg-green-500 transition-all flex items-center justify-center">
            <div class="h-2.5 w-2.5 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
        </div>
    </div>

    <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-green-500 pointer-events-none transition-all"></div>
</label>
