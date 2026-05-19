<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;

new #[Layout('layouts::auth')] class extends Component
{
    public User $user;
    public string $buttonRoute;

    public function mount(): void
    {
        $this->user = Auth::user();

        $flow = session('auth_flow');
        session()->forget(['auth_flow']);

        $this->buttonRoute = $flow === 'register' ? route('authentication.recovery-codes') : '';
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
            Acesso liberado!
        </h1>

        <span class="text-content text-primary-text text-center">
            Você entrou como <span class="font-semibold">{{ $user->email }}</span>. 
            <br>
            Bem vindo de volta.
        </span>
    </header>

    <div id="codeblock" class="w-full bg-background-secondary border border-border rounded-md p-4 space-y-0.5">
        <span class="text-code-comment">
            // sessão criada
        </span>
        <br>
        <span class="text-code">
            <span class="text-purple-500">const</span> user = &#123;
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;username: <span class="text-green-700">"{{ $user->username }}"</span>
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;streak: <span class="text-green-700">{{ $user->streak }}</span>
            <br>
            &#125;
        </span>
    </div>

    <x-actions.primary-button href="{{ $buttonRoute }}">Ir para o app</x-actions.primary-button>
</div>