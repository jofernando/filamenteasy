<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $isContained = true;
        $containers = $getChildComponentContainers();

        $addAction = $getAction($getAddActionName());
        $cloneAction = $getAction($getCloneActionName());
        $deleteAction = $getAction($getDeleteActionName());
        $moveDownAction = $getAction($getMoveDownActionName());
        $moveUpAction = $getAction($getMoveUpActionName());
        $reorderAction = $getAction($getReorderActionName());

        $isAddable = $isAddable();
        $isCloneable = $isCloneable();
        $isCollapsible = $isCollapsible();
        $isDeletable = $isDeletable();
        $isReorderableWithButtons = $isReorderableWithButtons();
        $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();

        $statePath = $getStatePath();
    @endphp

    <div
        wire:ignore.self
        x-cloak
        x-data="{
            step: null,

            init: function () {

                this.step = 0
            },

            nextStep: function () {
                this.step = this.step + 1

                this.scrollToTop()
            },

            previousStep: function () {
                this.step = this.step - 1

                this.scrollToTop()
            },

            isFirstStep: function() {
                return this.step == 0;
            },

            scrollToTop: function () {
                this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' })
            },
        }"
        {{
            $attributes
                ->merge([
                    'id' => $getId(),
                ], escape: false)
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-fo-wizard',
                    'fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => $isContained,
                ])
        }}
    >
        <ol
            @if (filled($label = $getLabel()))
                aria-label="{{ $label }}"
            @endif
            role="list"
            @class([
                'fi-fo-wizard-header grid divide-y divide-gray-200 dark:divide-white/5 md:grid-cols-4 md:divide-y-0',
                'border-b border-gray-200 dark:border-white/10' => $isContained,
                'rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => ! $isContained,
            ])
        >
            @foreach ($containers as $uuid => $step)
                <li class="fi-fo-wizard-header-step relative flex">
                    <button
                        type="button"
                        x-bind:aria-current="step === {{ $loop->index }} ? 'step' : null"
                        x-on:click="step = @js($loop->index)"
                        role="step"
                        class="flex h-full w-full items-center gap-x-4 px-6 py-4"
                    >
                        <div
                            class="fi-fo-wizard-header-step-icon-ctn flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                            x-bind:class="{
                                'bg-primary-600 dark:bg-primary-500':
                                    step > {{ $loop->index }},
                                'border-2': step <= {{ $loop->index }},
                                'border-primary-600 dark:border-primary-500':
                                    step === {{ $loop->index }},
                                'border-gray-300 dark:border-gray-600':
                                    step < {{ $loop->index }},
                            }"
                        >
                            <x-filament::icon
                                alias="forms::components.wizard.completed-step"
                                icon="heroicon-o-check"
                                x-cloak="x-cloak"
                                x-show="step > {{ $loop->index }}"
                                class="fi-fo-wizard-header-step-icon h-6 w-6 text-white"
                            />

                            <span
                                x-show="step <= {{ $loop->index }}"
                                class="text-sm font-medium"
                                x-bind:class="{
                                    'text-gray-500 dark:text-gray-400':
                                        step !== {{ $loop->index }},
                                    'text-primary-600 dark:text-primary-500':
                                        step === {{ $loop->index }},
                                }"
                            >
                                {{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="grid justify-items-start">
                            <span
                                class="fi-fo-wizard-header-step-label text-sm font-medium"
                                x-bind:class="{
                                    'text-gray-500 dark:text-gray-400':
                                        step < {{ $loop->index }},
                                    'text-primary-600 dark:text-primary-400':
                                        step === {{ $loop->index }},
                                    'text-gray-950 dark:text-white': step > {{ $loop->index }},
                                }"
                            >
                                Questão
                            </span>
                        </div>
                    </button>

                    @if (! $loop->last)
                        <div
                            aria-hidden="true"
                            class="absolute end-0 hidden w-5 md:block"
                        >
                            <svg
                                fill="none"
                                preserveAspectRatio="none"
                                viewBox="0 0 22 80"
                                class="h-full w-full text-gray-200 rtl:rotate-180 dark:text-white/5"
                            >
                                <path
                                    d="M0 -2L20 40L0 82"
                                    stroke-linejoin="round"
                                    stroke="currentcolor"
                                    vector-effect="non-scaling-stroke"
                                ></path>
                            </svg>
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>

        @foreach ($containers as $uuid => $step)
            @php
                $id = $loop->index;

                $visibleStepClasses = \Illuminate\Support\Arr::toCssClasses([
                    'p-6' => $isContained,
                    'mt-6' => ! $isContained,
                ]);

                $invisibleStepClasses = 'invisible h-0 overflow-y-hidden p-0';
            @endphp

            <div
                x-bind:class="step === @js($id) ? @js($visibleStepClasses) : @js($invisibleStepClasses)"
                x-on:expand-concealing-component.window="
                    error = $el.querySelector('[data-validation-error]')

                    if (! error) {
                        return
                    }

                    if (document.body.querySelector('[data-validation-error]') !== error) {
                        return
                    }

                    setTimeout(
                        () =>
                            $el.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start',
                                inline: 'start',
                            }),
                        200,
                    )
                "
                x-ref="step-{{ $id }}"
                {{
                    $attributes
                        ->merge([
                            'aria-labelledby' => $id,
                            'id' => $id,
                            'role' => 'tabpanel',
                            'tabindex' => '0',
                        ], escape: false)
                        ->merge($getExtraAttributes(), escape: false)
                        ->class(['fi-fo-wizard-step outline-none'])
                }}
            >
                {{ $step }}
                <div
                    @class([
                        'flex justify-between gap-x-3',
                        'px-6 py-6' => $isContained,
                        'mt-6' => ! $isContained,
                    ])
                >
                    <div>
                        <span
                            x-cloak
                            x-on:click="previousStep"
                            @class([
                                'hidden' => $loop->first
                            ])
                        >
                            <button type="button" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-btn-color-gray fi-size-sm fi-btn-size-sm gap-1 px-2.5 py-1.5 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action">Anterior</button>
                        </span>
                    </div>

                    <div>
                        <span
                            x-cloak
                            x-on:click="nextStep"
                            @class([
                                'hidden' => $loop->last
                            ])
                        >
                            <button type="button" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-btn-color-gray fi-size-sm fi-btn-size-sm gap-1 px-2.5 py-1.5 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action">Próximo</button>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
        <div
            @class([
                'flex items-center justify-center gap-x-3',
                'px-6 py-6' => $isContained,
                'mt-6' => ! $isContained,
            ])
        >
            @if ($isAddable)
                <div x-on:click="scrollToTop">
                    {{ $addAction }}
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>
