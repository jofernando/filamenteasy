<?php

namespace App\Livewire\Trabalhos;

use App\Models\Modalidade;
use App\Models\Trabalho;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTrabalho extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Modalidade $modalidade;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('area_id')
                    ->relationship('area', 'nome')
                    ->required(),
                Forms\Components\Repeater::make('coautores')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\FileUpload::make('arquivo')
                    ->required()
                    ->disk('local')
                    ->directory('trabalhos')
                    ->maxSize(2048),
            ])
            ->statePath('data')
            ->model(Trabalho::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $trabalho = new Trabalho();
        $trabalho->nome = $data['nome'];
        $trabalho->arquivo = $data['arquivo'];
        $trabalho->area_id = $data['area_id'];
        $trabalho->user_id = auth()->user()->id;
        $trabalho = $this->modalidade->trabalhos()->save($trabalho);
        if ($data['coautores']) {
            $coautores = collect([]);
            foreach ($data['coautores'] as $coautor) {
                if ($coautor['email']) {
                    $qry = User::where('email', $coautor['email']);
                    if ($qry->exists()) {
                        $coautores->push($qry->first()->id);
                    } else {
                        $password = Str::random(12);
                        $user = User::create([
                            'email' => $coautor['email'],
                            'password' => Hash::make($password),
                            'name' => $coautor['name'],
                        ]);
                        $coautores->push($user->id);
                    }
                }
            }
            $trabalho->coautores()->attach($coautores);
        }

        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.trabalhos.create-trabalho');
    }
}
