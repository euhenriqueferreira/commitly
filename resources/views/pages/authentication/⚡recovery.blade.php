<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\Authentication\RecoveryCodeService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';
    public string $recoveryCode = '';

    public function verify(): void
    {
        $key = 'recovery-code:' . $this->throttleKey();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'recoveryCode' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        RateLimiter::hit($key, 300);

        $this->validate([
            'email' => ['required', 'email', 'max:255'],
            'recoveryCode' => ['required', 'string', 'max:12'],
        ]);

        $user = User::query()
            ->where('email', $this->email)
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'recoveryCode' => 'Credenciais inválidas.',
            ]);
        }

        $normalizedCode = strtoupper(str_replace(' ', '', trim($this->recoveryCode)));
        
        $recoveryCodes = $user->recoveryCodes()
            ->whereNull('used_at')
            ->get();

            
        $matchedCode = $recoveryCodes->first(fn ($code) => Hash::check($normalizedCode, $code->code_hash));

        if (! $matchedCode) {
            throw ValidationException::withMessages([
                'recoveryCode' => 'Código inválido.',
            ]);
        }

        RateLimiter::clear($key);

        $matchedCode->update(['used_at' => now()]);

        session('recovery_authorized', true);

        Auth::login($user);

        $this->redirectRoute('authentication.email-reset');
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
            Recuperar acesso
        </h1>

        <span class="text-content text-primary-text text-center">
            Não consegue acessar seu e-mail? <br> Insira seu e-mail de cadastro e um código de recuperação no campo abaixo.
        </span>
    </header>

    <form class="w-full space-y-3" wire:submit="verify">
        <div class="space-y-1">
            <x-form.input-text wireModel="email" label="E-mail" placeholder="seu@email.com" />
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