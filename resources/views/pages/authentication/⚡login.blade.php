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
            Entrar no Commitly
        </h1>

        <span class="text-content text-primary-text text-center">
            Insira seu e-mail e enviaremos <br> um código de acesso.
        </span>
    </header>

    <form class="w-full space-y-3">
        <div class="space-y-1">
            <x-form.input-text wireModel="email" label="E-mail" placeholder="seu@email.com" />
            
            <div class="w-full text-right">
                <a href="" class="text-small text-secondary-text text-right">Perdeu acesso ao seu e-mail?</a>
            </div>
        </div>

        <x-actions.button>Enviar código</x-actions.button>

        <div class="text-center">
            <span class="text-small text-secondary-text">
                Ao continuar, você concorda com os <a href="" class="underline">Termos de uso</a> e <a href="" class="underline">Política de privacidade</a>.
            </span>
        </div>
    </form>

    <a href="{{ route('authentication.register') }}" wire:navigate class="text-small text-primary-text">
        Não tem uma conta? 
        <span class="font-semibold underline">
            Criar conta
        </span>
    </a>
</div>