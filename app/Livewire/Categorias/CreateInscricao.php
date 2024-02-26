<?php

namespace App\Livewire\Categorias;

use App\Models\Inscricao\Categoria;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;

class CreateInscricao extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Categoria $categoria;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->categoria->components())
            ->statePath('data');
    }

    public function create(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.categorias.create-inscricao');
    }
}
