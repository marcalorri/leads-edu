<?php

namespace Tests\Feature\Api\V1;

use App\Models\ApiToken;
use App\Models\Lead;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Course;
use App\Models\Campus;
use App\Models\Modality;
use App\Models\Origin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private Tenant $tenant;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear tenant y usuario de prueba
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create();
        
        // Asociar usuario al tenant
        $this->user->tenants()->attach($this->tenant);

        // Crear token API con todos los permisos
        $tokenResult = $this->user->createToken('test-token', [
            'leads:read',
            'leads:write',
            'leads:delete',
            'leads:admin'
        ]);

        // Actualizar el token con tenant_id
        ApiToken::find($tokenResult->accessToken->id)->update([
            'tenant_id' => $this->tenant->id
        ]);

        $this->token = $tokenResult->plainTextToken;

        // Crear datos de prueba necesarios
        $this->createTestData();
    }

    private function createTestData(): void
    {
        // Crear curso de prueba
        Course::factory()->create([
            'tenant_id' => $this->tenant->id,
            'codigo_curso' => 'TEST-001',
            'titulacion' => 'Curso de Prueba'
        ]);

        // Crear sede de prueba
        Campus::factory()->create([
            'tenant_id' => $this->tenant->id,
            'nombre' => 'Sede Test',
            'codigo' => 'TEST'
        ]);

        // Crear modalidad de prueba
        Modality::factory()->create([
            'tenant_id' => $this->tenant->id,
            'nombre' => 'Online',
            'codigo' => 'ON'
        ]);

        // Crear origen de prueba
        Origin::factory()->create([
            'tenant_id' => $this->tenant->id,
            'nombre' => 'Web',
            'tipo' => 'web'
        ]);
    }

    /** @test */
    public function it_can_list_leads_with_valid_token()
    {
        // Crear algunos leads de prueba
        Lead::factory()->count(3)->create([
            'tenant_id' => $this->tenant->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/leads');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'nombre',
                            'apellidos',
                            'email',
                            'telefono',
                            'estado',
                            'created_at'
                        ]
                    ],
                    'links',
                    'meta'
                ]);
    }

    /** @test */
    public function it_can_create_lead_with_valid_data()
    {
        $course = Course::where('tenant_id', $this->tenant->id)->first();
        $campus = Campus::where('tenant_id', $this->tenant->id)->first();
        $modality = Modality::where('tenant_id', $this->tenant->id)->first();
        $origin = Origin::where('tenant_id', $this->tenant->id)->first();

        $leadData = [
            'nombre' => 'Juan',
            'apellidos' => 'Pérez García',
            'email' => 'juan.perez@test.com',
            'telefono' => '+34600123456',
            'curso_id' => $course->id,
            'sede_id' => $campus->id,
            'modalidad_id' => $modality->id,
            'origen_id' => $origin->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/leads', $leadData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'nombre',
                        'apellidos',
                        'email',
                        'telefono',
                        'estado',
                        'tenant_id'
                    ]
                ]);

        $this->assertDatabaseHas('leads', [
            'email' => 'juan.perez@test.com',
            'tenant_id' => $this->tenant->id
        ]);
    }

    /** @test */
    public function it_rejects_requests_without_token()
    {
        $response = $this->getJson('/api/v1/leads');

        $response->assertStatus(401)
                ->assertJson([
                    'error' => [
                        'code' => 'UNAUTHENTICATED'
                    ]
                ]);
    }

    /** @test */
    public function it_rejects_requests_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
            'Accept' => 'application/json',
        ])->getJson('/api/v1/leads');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_lead()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/leads', []);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error' => [
                        'code',
                        'message',
                        'details'
                    ]
                ]);
    }

    /** @test */
    public function it_prevents_duplicate_emails_in_same_tenant()
    {
        $course = Course::where('tenant_id', $this->tenant->id)->first();
        $campus = Campus::where('tenant_id', $this->tenant->id)->first();
        $modality = Modality::where('tenant_id', $this->tenant->id)->first();
        $origin = Origin::where('tenant_id', $this->tenant->id)->first();

        // Crear primer lead
        Lead::factory()->create([
            'tenant_id' => $this->tenant->id,
            'email' => 'duplicate@test.com'
        ]);

        // Intentar crear segundo lead con mismo email
        $leadData = [
            'nombre' => 'María',
            'apellidos' => 'González',
            'email' => 'duplicate@test.com',
            'telefono' => '+34600654321',
            'curso_id' => $course->id,
            'sede_id' => $campus->id,
            'modalidad_id' => $modality->id,
            'origen_id' => $origin->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/leads', $leadData);

        $response->assertStatus(422)
                ->assertJsonPath('error.details.email.0', 'Ya existe un lead con este email en el tenant.');
    }

    /** @test */
    public function it_can_show_specific_lead()
    {
        $lead = Lead::factory()->create([
            'tenant_id' => $this->tenant->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->getJson("/api/v1/leads/{$lead->id}");

        $response->assertStatus(200)
                ->assertJsonPath('data.id', $lead->id)
                ->assertJsonPath('data.email', $lead->email);
    }

    /** @test */
    public function it_can_update_lead()
    {
        $lead = Lead::factory()->create([
            'tenant_id' => $this->tenant->id,
            'nombre' => 'Original'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->putJson("/api/v1/leads/{$lead->id}", [
            'nombre' => 'Actualizado'
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.nombre', 'Actualizado');

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'nombre' => 'Actualizado'
        ]);
    }

    /** @test */
    public function it_can_delete_lead()
    {
        $lead = Lead::factory()->create([
            'tenant_id' => $this->tenant->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/leads/{$lead->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('leads', [
            'id' => $lead->id
        ]);
    }

    /** @test */
    public function it_prevents_cross_tenant_access()
    {
        // Crear otro tenant y lead
        $otherTenant = Tenant::factory()->create();
        $otherLead = Lead::factory()->create([
            'tenant_id' => $otherTenant->id
        ]);

        // Intentar acceder al lead del otro tenant
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->getJson("/api/v1/leads/{$otherLead->id}");

        $response->assertStatus(404);
    }
}
