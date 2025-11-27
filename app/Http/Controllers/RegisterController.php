<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Licenca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Notifications\ResetPassword;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register-company');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'unique:empresas,cnpj', function ($attribute, $value, $fail) {
                $cleanValue = preg_replace('/[^0-9]/', '', (string) $value);
                
                if (strlen($cleanValue) == 11) {
                    if (!$this->validarCpf($cleanValue)) {
                        $fail('O CPF fornecido não é válido.');
                    }
                } elseif (strlen($cleanValue) == 14) {
                    if (!$this->validarCnpj($cleanValue)) {
                        $fail('O CNPJ fornecido não é válido.');
                    }
                } else {
                    $fail('O documento deve conter 11 (CPF) ou 14 (CNPJ) dígitos.');
                }
            }],
            'email_contato' => 'required|email|max:255|unique:users,email',
            'telefone_contato' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // 1. Criar a Empresa (ou Pessoa Física atuando como empresa)
            $empresa = Empresa::create($validatedData);

            // 2. Criar o Usuário Master
            $password = Str::random(10);
            $user = User::create([
                'name' => 'Master ' . $empresa->nome_fantasia,
                'email' => $empresa->email_contato,
                'password' => Hash::make($password),
                'id_empresa' => $empresa->id,
                'role' => 'master',
                'email_verified_at' => now(),
            ]);

            // 3. Criar a Licença Trial diretamente
            Licenca::create([
                'id_empresa' => $empresa->id,
                'plano' => 'Trial',
                'id_usuario_criador' => null,
                'valor_pago' => 0.00,
                'data_inicio' => Carbon::today(),
                'data_vencimento' => Carbon::today()->addDays(30),
                'status' => 'ativo',
            ]);
            
            // Em ambiente real, enviaríamos o e-mail aqui.
            // $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
            // $user->notify(new ResetPassword($token));
            
            // Log para debug (Remover em produção se enviar e-mail real)
            Log::info("Novo usuário registrado: Email {$user->email}, Senha temporária: {$password}");

            DB::commit();

            // Autenticar o usuário recém-criado
            auth()->login($user);

            return redirect()->route('dashboard')->with('status', 'Conta criada com sucesso! Bem-vindo ao Frotamaster.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro no registro de empresa: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocorreu um erro inesperado ao criar a conta. Por favor, tente novamente.');
        }
    }

    private function validarCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    private function validarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
        if (strlen($cpf) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}