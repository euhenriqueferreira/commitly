<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::auth')] class extends Component
{};
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
            Recuperar acesso
        </h1>

        <span class="text-content text-primary-text text-center">
            Não consegue acessar seu e-mail? Insira um <br> código de recuperação no campo abaixo.
        </span>
    </header>

    <form class="w-full space-y-3">
        <div class="space-y-1">
            <x-form.input-text wireModel="recoveryCode" label="Código de recuperação" placeholder="XXXX-XXXX" />
        </div>

        <x-actions.primary-button>Verificar código</x-actions.primary-button>
    </form>

    <a href="{{ route('authentication.login') }}" wire:navigate class="text-small text-primary-text">
        Tem acesso ao e-mail? 
        <span class="font-semibold underline">
            Entrar
        </span>
    </a>
</div>