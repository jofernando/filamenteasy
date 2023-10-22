<x-layout>
    <div class="grid grid-cols-12 gap-5 grid-flow-row">
        <div class="col-span-12 md:col-span-9">
            <div class="rounded-xl border-2 border-stone-300 bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                <div class="rounded-t-lg bg-stone-200 p-0 m-0 border-b-2 border-stone-300">
                    <div class="p-4">
                        <h1 class="text-center font-bold text-lg">{{ $evento->nome }}</h1>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('storage/'.$evento->logo) }}" alt="logo evento">
                    <div class="px-6 pb-6 prose block w-full max-w-none">{!! $evento->descricao !!}</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-3">
            <div class="flex space-y-5 flex-col">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6 flex flex-col space-y-5">
                        <h1 class="font-bold text-md">{{ __('Arquivos') }}</h1>
                        @foreach ($evento->arquivos as $arquivo)
                            <a href="{{ asset('storage/'.$arquivo->path) }}"
                                class="relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50"
                                target="_blank"
                            >{{ $arquivo->nome }}</a>
                        @endforeach
                    </div>
                </div>
                <h1 class="font-bold text-md pt-3">{{ __('Submissões de tabalho') }}</h1>
                @foreach ($evento->modalidades as $modalidade)
                    <div
                         x-data="{mod{{ $modalidade->id }}: false}"
                         class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div class="flex flex-col">
                            <h1 class="p-6 cursor-pointer flex justify-between items-center" x-on:click="mod{{ $modalidade->id }} = ! mod{{ $modalidade->id }}">
                                {{ $modalidade->nome }}
                                <i class="fa-solid fa-chevron-up" x-show="mod{{ $modalidade->id }}"></i>
                                <i class="fa-solid fa-chevron-down" x-show="! mod{{ $modalidade->id }}"></i>
                            </h1>
                            <div x-show="mod{{ $modalidade->id }}" x-collapse class="cursor-default mx-6 mb-6 flex space-y-3 flex-col">
                                <div>
                                    <i class="fa-regular fa-calendar-plus fa-xl mr-1"></i> Submissão
                                    <p class="text-sm">{{ $modalidade->submissao_inicio->format('d/m/Y') }} - {{ $modalidade->submissao_fim->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <i class="fa-regular fa-calendar-check fa-xl mr-1"></i> Avaliação
                                    <p class="text-sm">{{ $modalidade->avaliacao_inicio->format('d/m/Y') }} - {{ $modalidade->avaliacao_fim->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <i class="fa-regular fa-calendar fa-xl mr-1"></i> Resultado
                                    <p class="text-sm">{{ $modalidade->resultado->format('d/m/Y') }}</p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('trabalhos.create', ['evento'=> $evento, 'modalidade' => $modalidade]) }}"
                                        class="relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50"
                                    >
                                        {{ __('Submeter trabalho') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>
