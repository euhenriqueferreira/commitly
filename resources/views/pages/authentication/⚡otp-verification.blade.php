<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Services\Authentication\OtpService;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email;
    public array $digits = ['', '', '', '', '', ''];

    public function mount()
    {
        $user = User::find(session('otp_user_id'));
        $this->email = $user?->email ?? '';
    }

    public function verify(OtpService $otpService)
    {
        $code = $this->code;

        $userId = session('otp_user_id');
        $type = session('otp_type');

        abort_if(! $userId || ! $type, 403);

        $user = User::findOrFail($userId);

        $isValid = $otpService->validate(
            user: $user,
            email: $user->email,
            code: $code,
            type: $type
        );

        if (! $isValid) {
            $this->addError('otp', 'Código inválido ou expirado.');
            return;
        }

        Auth::login($user);

        session()->forget(['otp_user_id', 'otp_type']);
        session()->regenerate();

        $this->redirectRoute('authentication.otp-success');
    }

    public function resend(OtpService $otpService)
    {
        $userId = session('otp_user_id');
        $type = session('otp_type');

        abort_if(! $userId || ! $type, 403);

        $user = User::findOrFail($userId);

        $otpService->generate(
            user: $user,
            email: $user->email,
            type: $type
        );

        $this->dispatch('auth:otp-resend');
    }

    public function updatedDigits()
    {
        $this->digits = array_map( fn ($v) => preg_replace('/\D/', '', $v), $this->digits);
    }

    public function getCodeProperty(): string
    {
        return implode('', $this->digits);
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
            Verifique seu e-mail
        </h1>

        <span class="text-content text-primary-text text-center">
            Enviamos um código de 6 dígitos para <span class="font-semibold">{{ $email }}</span>. 
            <br>
            Ele expira em 10 minutos.
        </span>
    </header>

    <form class="w-full space-y-3" wire:submit.prevent="verify">
        <div
            x-data="{
                focusNext(index) {if (this.$refs['input' + (index + 1)]) this.$refs['input' + (index + 1)].focus();},
                focusPrev(index) {if (this.$refs['input' + (index - 1)]) this.$refs['input' + (index - 1)].focus();}
            }"
            class="w-full grid grid-cols-6 gap-2 justify-items-center"
        >
            @foreach (array_keys($digits) as $i)
                <div class="w-12 h-12">
                    <x-form.input-text 
                        wireModel="digits.{{ $i }}" 
                        wireType="live" 
                        x-ref="input{{ $i }}" 
                        inputmode="numeric" 
                        class="w-full !text-center"
                        maxlength="1"
                        
                        @input="if ($el.value.replace(/\D/g, '')) focusNext({{ $i }});"
                        @keydown.backspace="if (!$el.value) focusPrev({{ $i }});"
                    />
                </div>
            @endforeach
        </div>

        <x-actions.primary-button loading="verify">Verificar</x-actions.primary-button>
    

        <div
            x-data="{
                seconds: {{ config('auth.otp.resend_cooldown') }},
                interval: null,

                init() {
                    this.startTimer();
                },

                startTimer() {
                    this.seconds = {{ config('auth.otp.resend_cooldown') }};
                    this.interval = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                        } else {
                            clearInterval(this.interval);
                        }
                    }, 1000);
                },

                resetTimer() {
                    clearInterval(this.interval);
                    this.startTimer();
                },

                formatTime() {
                    const m = Math.floor(this.seconds / 60);
                    const s = this.seconds % 60;
                    return `${m}:${String(s).padStart(2, '0')}`;
                }
            }"
            x-on:auth:otp-resend.window="resetTimer()"
            class="flex flex-col gap-3 items-center"
        >
            <span class="text-small text-secondary-text">
                Reenviar código em
                <span class="bg-background-secondary py-0.5 px-2 rounded-sm border border-border">
                    <span x-text="formatTime()"></span>
                </span>
            </span>

            <x-actions.secondary-button size="fit" x-bind:disabled="seconds > 0"  x-bind:class="seconds > 0 ? 'opacity-65 cursor-not-allowed' : 'opacity-100 hover:brightness-95 cursor-pointer'" wire:click="resend">
                Reenviar agora
            </x-actions.secondary-button>
        </div>
    </form>

    <a href="{{ route('authentication.login') }}" wire:navigate class="text-small text-primary-text underline">
        Voltar para o login
    </a>
</div>