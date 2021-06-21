<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Repositories\IdentificationTypeRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class IdentificationTypeApiTest extends TestCase
{
    use WithFaker;

    /** @var IdentificationTypeRepository */
    protected $identificationTypeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->identificationTypeRepository = app(IdentificationTypeRepository::class);
    }

    public function test_store_identification_type()
    {
        $name = $this->faker->word;
        $description = $this->faker->word;

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->post('/api/v1/identificationTypes', ['name' => $name, 'description' => $description]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_update_identification_type(){
       $identificationType = $this->identificationTypeRepository->create([
            'name'          => $this->faker->word,
            'description'   => $this->faker->word
        ]);

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->put("/api/v1/identificationTypes/{$identificationType->id}", ['name' => $this->faker->word, 'description' => $this->faker->word]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_delete_identification_type(){
        $identificationType = $this->identificationTypeRepository->create([
            'name'          => $this->faker->word,
            'description'   => $this->faker->word
        ]);

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->delete("/api/v1/identificationTypes/{$identificationType->id}", []);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }
}
