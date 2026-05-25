<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\Authentication\RegisterService;
use App\Enum\Authentication\Otp\OtpVerificationTypeEnum;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';
    public string $name = '';
    public string $username = '';

    public function register(RegisterService $registerService)
    {
        $validated = $this->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
        ]);

        $user = $registerService->register($validated);

        session([
            'otp_user_id' => $user->id,
            'otp_type' => OtpVerificationTypeEnum::LOGIN->value,
            'auth_flow' => 'register',
        ]);

        $this->redirectRoute('authentication.otp-verification');
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
           Criar conta
        </h1>

        <span class="text-content text-primary-text text-center">
            Leva menos de um minuto.
        </span>
    </header>

    <form class="w-full space-y-3" wire:submit.prevent="register">
        <div class="space-y-1">
            <x-form.input-text wireModel="email" label="E-mail" placeholder="seu@email.com" />
            <x-form.input-text wireModel="name" label="Nome" placeholder="Seu nome" />
            <x-form.input-text wireModel="username" wireType="live" label="Nome de usuário" placeholder="@seu-username" />
        </div>

        <div id="codeblock" class="bg-background-secondary border border-border rounded-md p-4 space-y-0.5">
            <span class="text-code-comment">
                // seu perfil público
            </span>
            <br>
            <span class="text-code">
                <span class="text-purple-500">const</span> user = &#123;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;username: <span class="text-green-700">"{{ $username }}"</span>
                <br>
                &#125;
            </span>
        </div>

        <x-actions.primary-button type="submit">
            Criar conta e verificar e-mail
        </x-actions.primary-button>
    </form>

    <a href="{{ route('authentication.login') }}" wire:navigate class="text-small text-primary-text">
        Já tem uma conta?
        <span class="font-semibold underline">
            Entrar
        </span>
    </a>
</div>