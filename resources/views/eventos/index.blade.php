<x-layout>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($eventos as $evento)
            <div>
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                    <div class="p-6 grid gap-5">
                        <h1 class="text-center font-bold text-lg">{{ $evento->nome }}</h1>
                        <img src="{{ asset('storage/'.$evento->logo) }}" alt="logo evento">
                        <div class="flex justify-center">
                            <a href="{{ route('eventos.show', $evento) }}" class="relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20">
                                Visualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
