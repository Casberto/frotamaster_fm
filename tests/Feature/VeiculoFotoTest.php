<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Veiculo;
use App\Models\VeiculoFoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VeiculoFotoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function createVehicle($user)
    {
        return Veiculo::create([
            'vei_emp_id' => $user->id_empresa,
            'vei_placa' => 'ABC1234',
            'vei_modelo' => 'Test Car',
            'vei_marca' => 'Test Brand',
            'vei_chassi' => '12345678901234567',
            'vei_renavam' => '12345678901',
            'vei_ano_fab' => 2020,
            'vei_ano_mod' => 2020,
            'vei_fabricante' => 'Test Manufacturer',
            'vei_tipo' => 6,
            'vei_combustivel' => 1,
            'vei_especie' => 1,
            'vei_carroceria' => 1,
            'vei_cor_predominante' => 'White',
            'vei_data_aquisicao' => now(),
            'vei_km_atual' => 10000,
            'vei_status' => 1, // 1-Ativo
            'vei_user_id' => $user->id,
            'vei_segmento' => 1, // 1-Particular
        ]);
    }

    private function createEmpresa()
    {
        // Assuming Empresa model exists and has a factory or can be created manually
        // If factory exists:
        // return \App\Models\Empresa::factory()->create();
        
        // Manual creation if no factory or to be safe:
        $empresa = new \App\Models\Empresa();
        $empresa->nome_fantasia = 'Test Co';
        $empresa->razao_social = 'Test Company';
        $empresa->cnpj = '12345678000199';
        $empresa->email_contato = 'test@company.com';
        $empresa->telefone_contato = '11999999999';
        // $empresa->status_pagamento = 'ativo';
        $empresa->save();

        // Create active license
        \App\Models\Licenca::create([
            'id_empresa' => $empresa->id,
            'plano' => 'Mensal',
            'data_inicio' => now(),
            'data_vencimento' => now()->addMonth(),
            'status' => 'ativo',
        ]);
        
        return $empresa;
    }

    public function test_upload_photo()
    {
        $empresa = $this->createEmpresa();
        $user = User::factory()->create([
            'id_empresa' => $empresa->id,
        ]);
        $veiculo = $this->createVehicle($user);

        $file = UploadedFile::fake()->create('car.jpg', 100); // 100kb, no image() call which needs GD

        $response = $this->actingAs($user)
            ->postJson(route('veiculos.fotos.store', $veiculo->vei_id), [
                'file' => $file,
            ]);

        $response->assertStatus(200);

        // Check DB
        $this->assertDatabaseHas('veiculos_fotos', [
            'vef_vei_id' => $veiculo->vei_id,
        ]);

        // Check Storage
        $foto = VeiculoFoto::where('vef_vei_id', $veiculo->vei_id)->first();
        Storage::assertExists($foto->arquivo);
    }

    public function test_list_photos()
    {
        $empresa = $this->createEmpresa();
        $user = User::factory()->create([
            'id_empresa' => $empresa->id,
        ]);
        $veiculo = $this->createVehicle($user);
        
        // Manually create a photo
        $path = "uploads/{$user->id_empresa}/veiculos/{$veiculo->vei_id}/test.jpg";
        Storage::put($path, 'content');
        
        VeiculoFoto::create([
            'vef_vei_id' => $veiculo->vei_id,
            'arquivo' => $path,
            'vef_criado_em' => now(),
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('veiculos.fotos.index', $veiculo->vei_id));

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_delete_photo()
    {
        $empresa = $this->createEmpresa();
        $user = User::factory()->create([
            'id_empresa' => $empresa->id,
        ]);
        $veiculo = $this->createVehicle($user);
        
        $path = "uploads/{$user->id_empresa}/veiculos/{$veiculo->vei_id}/delete_me.jpg";
        Storage::put($path, 'content');
        
        $foto = VeiculoFoto::create([
            'vef_vei_id' => $veiculo->vei_id,
            'arquivo' => $path,
            'vef_criado_em' => now(),
        ]);

        $response = $this->actingAs($user)
            ->deleteJson(route('veiculos.fotos.destroy', $foto->vef_id));

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('veiculos_fotos', ['vef_id' => $foto->vef_id]);
        Storage::assertMissing($path);
    }

    public function test_vehicle_deletion_cleans_up_photos()
    {
        $empresa = $this->createEmpresa();
        $user = User::factory()->create([
            'id_empresa' => $empresa->id,
        ]);
        $veiculo = $this->createVehicle($user);
        
        $path = "uploads/{$user->id_empresa}/veiculos/{$veiculo->vei_id}/cleanup.jpg";
        Storage::put($path, 'content');
        
        VeiculoFoto::create([
            'vef_vei_id' => $veiculo->vei_id,
            'arquivo' => $path,
            'vef_criado_em' => now(),
        ]);

        // Use the service to delete the vehicle to trigger the cleanup logic
        $service = app(\App\Services\VeiculoService::class);
        $this->actingAs($user); // Set auth user for service check
        $service->deletarVeiculo($veiculo);

        $this->assertDatabaseMissing('veiculos', ['vei_id' => $veiculo->vei_id]);
        
        $dir = "uploads/{$user->id_empresa}/veiculos/{$veiculo->vei_id}";
        $this->assertEmpty(Storage::allFiles($dir));
    }
}
