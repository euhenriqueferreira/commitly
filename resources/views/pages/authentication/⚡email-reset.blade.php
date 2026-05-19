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
            Redefinir e-mail de acesso
        </h1>

        <span class="text-content text-primary-text text-center">
            Insira um novo e-mail ao qual você tem acesso.
        </span>
    </header>

    <form class="w-full space-y-3">
        <div class="space-y-1">
            <x-form.input-text wireModel="newEmail" label="Novo e-mail" placeholder="seu@email.com" />
        </div>

        <x-actions.primary-button>Redefinir e enviar código</x-actions.primary-button>
    </form>
</div>