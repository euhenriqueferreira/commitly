<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Services\Authentication\OtpService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Enum\Authentication\Otp\OtpVerificationType;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';

    public function submit(OtpService $otpService)
    {
        $this->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);


        $key = 'login:' . $this->throttleKey();


        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        $user = User::query()
            ->where('email', $this->email)
            ->first();

        if (!$user) {
            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'email' => 'Nenhuma conta encontrada com este e-mail.',
            ]);
        }

        RateLimiter::clear($key);

        $otpService->generate(
                user: $user,
                email: $user->email,
                type: OtpVerificationType::LOGIN->value
            );

        session([
            'otp_user_id' => $user->id,
            'otp_type' => OtpVerificationType::LOGIN->value,
            'auth_flow' => 'login',
        ]);

        return redirect()->route('authentication.otp-verification');
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->email) . '|' . request()->ip();
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
            Entrar no Commitly
        </h1>

        <span class="text-content text-primary-text text-center">
            Insira seu e-mail e enviaremos <br> um código de acesso.
        </span>
    </header>

    <form class="w-full space-y-3" wire:submit="submit">
        <div class="space-y-1">
            <x-form.input-text wireModel="email" label="E-mail" placeholder="seu@email.com" autocomplete="email" />
            
            <div class="w-full text-right">
                <a href="{{ route('authentication.recovery') }}" wire:navigate class="text-small text-secondary-text text-right">Perdeu acesso ao seu e-mail?</a>
            </div>
        </div>

        <x-actions.primary-button>Enviar código</x-actions.primary-button>

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