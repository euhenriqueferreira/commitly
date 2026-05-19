<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::auth')] class extends Component
{
    public array $recoveryCodes;

    public function mount()
    {
        $this->recoveryCodes = ['A3F2-9KL1', 'BX7M-2P4Q', 'C9N8-6RT5', 'D1W3-7YH0', 'E5J4-3MZ8', 'F8U6-1SV2', 'G0Q9-4XB7', 'H2L7-8DK3'];
    }
};
?>


<div class="contents">
    <header class="flex flex-col items-center gap-6">
        <img
            src="{{ asset('assets/images/logomarca-animada-commitly.svg') }}" 
            alt="Logomarca Commitly" 
            title="Logomarca Commitly"
            class="w-20 h-20 object-scale-down"     
        >

        <h1 class="text-heading-1 text-primary-text text-center">
            Guarde seus códigos
        </h1>

        <x-structure.alert type="alert">
            Salve estes códigos agora. Cada um pode ser usado uma única vez para recuperar sua conta se perder acesso ao e-mail.
        </x-structure.alert>
    </header>

    <div class="w-full grid grid-cols-2 gap-2">
        @foreach ($recoveryCodes as $code)
            <div class="w-full text-center bg-background-secondary border border-border px-4 py-1.5 rounded-sm">
                <span class="text-code text-primary-text text-center">
                    {{ $code }}
                </span>
            </div>
        @endforeach
    </div>

    <div class="w-full space-y-3">    
        <x-actions.outline-button>Copiar todos</x-actions.outline-button>
        <x-actions.primary-button>Já salvei, continuar</x-actions.primary-button>
    </div>
</div>