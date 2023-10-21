<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Repeater;

class RepeaterWizard extends Repeater
{
    protected string $view = 'forms.components.repeater-wizard';

    protected bool | Closure $isReorderable = false;

    protected bool | Closure $isReorderableWithDragAndDrop = false;

    protected bool | Closure $isReorderableWithButtons = false;
}
