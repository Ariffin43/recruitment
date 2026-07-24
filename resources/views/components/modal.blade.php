<div 
    x-data="{ open: false }"
    x-cloak
    x-on:open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail.id === '{{ $id }}') open = false"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- Overlay -->
    <div 
        x-show="open"
        x-transition.opacity
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    <!-- Modal -->
    <div 
        x-show="open"
        x-transition.scale
        @click.away="open = false"
        class="relative bg-white w-full max-w-lg mx-4 rounded-2xl shadow-xl overflow-hidden"
    >
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">
                {{ $title }}
            </h2>

            <button @click="open = false" class="text-gray-400 hover:text-red-500">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @isset($footer)
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>