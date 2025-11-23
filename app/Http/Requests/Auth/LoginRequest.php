<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Tenta autenticar as credenciais básicas (Email/Senha)
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // 2. Verificação Adicional: Vínculo com Perfil
        $user = Auth::user();

        // A validação de perfil é aplicada SOMENTE para usuários comuns (role 'usuario').
        // 'super-admin' e 'master' não passam por esta verificação de vínculo de perfil para logar.
        if ($user->role === 'usuario') {
            
            // Verifica se o usuário possui perfis vinculados através do relacionamento definido no Model User
            // A relação perfis() usa a tabela pivô 'usuario_perfis'
            if ($user->perfis()->count() === 0) {
                
                // Se não tiver perfil, desloga imediatamente para matar a sessão criada pelo Auth::attempt
                Auth::guard('web')->logout();
                
                // Incrementa o RateLimiter para evitar brute-force de verificação de perfis
                RateLimiter::hit($this->throttleKey());

                // Retorna o erro para a tela de login
                throw ValidationException::withMessages([
                    'email' => 'Este usuário não possui um perfil de acesso vinculado. Contate o administrador.',
                ]);
            }
        }

        // Se passou por tudo, limpa o limitador de tentativas
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}