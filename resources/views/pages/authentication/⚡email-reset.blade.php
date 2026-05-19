<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\Authentication\OtpService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Enum\Authentication\Otp\OtpVerificationType;

new #[Layout('layouts::auth')] class extends Component
{
    public string $newEmail = '';

    public function resetEmail(OtpService $otpService): void
    {
        $key = 'recovery-code:' . $this->throttleKey();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'newEmail' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        RateLimiter::hit($key, 60);

        $this->validate([
            'newEmail' => ['required', 'email', 'max:255', 'unique:users,email',],
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user) {
            $user->update([
                'email' => $this->newEmail,
            ]);

            // Excluir sessões antigas em outros dispositivos   
            DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', session()->getId())
            ->delete();
        });

        RateLimiter::clear($key);

        $otpService->generate(
            user: $user,
            email: $user->email,
            type: OtpVerificationType::RECOVERY->value
        );

        session([
            'otp_user_id' => $user->id,
            'otp_type' => OtpVerificationType::RECOVERY->value,
            'auth_flow' => 'recovery',
        ]);

        $this->redirectRoute('authentication.otp-verification');
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->newEmail) . '|' . request()->ip();
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
            Redefinir e-mail de acesso
        </h1>

        <span class="text-content text-primary-text text-center">
            Insira um novo e-mail ao qual você tem acesso.
        </span>
    </header>

    <form class="w-full space-y-3" wire:submit="resetEmail">
        <div class="space-y-1">
            <x-form.input-text wireModel="newEmail" label="Novo e-mail" placeholder="seu@email.com" />
        </div>

        <x-actions.primary-button>Redefinir e enviar código</x-actions.primary-button>
    </form>
</div>