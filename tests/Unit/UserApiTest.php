<?php

namespace Tests\Unit;

use App\Repositories\IdentificationTypeRepository;
use App\Repositories\UserRepository;
//use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use WithFaker;

    /** @var UserRepository */
    protected $userRepository;

    /** @var IdentificationTypeRepository */
    protected $identificationTypeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
        $this->identificationTypeRepository = app(IdentificationTypeRepository::class);
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_store_user()
    {
        $identificationType = $this->identificationTypeRepository->create([
            'name'          => $this->faker->word,
            'description'   => $this->faker->word
        ]);

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->post('/api/v1/users', [
            'identification_type_id' => $identificationType->id,
            'first_name'             => $this->faker->name,
            'last_name'              => $this->faker->lastName,
            'email'                  => $this->faker->email,
            'phone'                  => $this->faker->phoneNumber,
            'birthday'               => $this->faker->date('Y-m-d')
        ]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function test_update_user()
    {

        $identificationType = $this->identificationTypeRepository->create([
            'name'          => $this->faker->word,
            'description'   => $this->faker->word
        ]);

        $this->userRepository->create(
            $identificationType->id,
            $this->faker->name,
            $this->faker->lastName,
            $this->faker->email,
            $this->faker->phoneNumber,
            $this->faker->date('Y-m-d'),
            Carbon::now(),
            "123456"
        );

        $user = $this->userRepository->selectLast();

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->put("api/v1/users/{$user[0]->id}", [
            'identification_type_id' => $identificationType->id,
            'first_name'             => $this->faker->name,
            'last_name'              => $this->faker->lastName,
            'email'                  => $this->faker->email,
            'phone'                  => $this->faker->phoneNumber,
            'birthday'               => $this->faker->date('Y-m-d')
        ]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

    }

    public function test_delete_user()
    {

        $identificationType = $this->identificationTypeRepository->create([
            'name'          => $this->faker->word,
            'description'   => $this->faker->word
        ]);

        $this->userRepository->create(
            $identificationType->id,
            $this->faker->name,
            $this->faker->lastName,
            $this->faker->email,
            $this->faker->phoneNumber,
            $this->faker->date('Y-m-d'),
            Carbon::now(),
            "123456"
        );

        $user = $this->userRepository->selectLast();

        $response = $this->withHeaders([
            'API-KEY-LAIKA' => '0123456789'
        ])->delete("api/v1/users/{$user[0]->id}", [
            'identification_type_id' => $identificationType->id,
            'first_name'             => $this->faker->name,
            'last_name'              => $this->faker->lastName,
            'email'                  => $this->faker->email,
            'phone'                  => $this->faker->phoneNumber,
            'birthday'               => $this->faker->date('Y-m-d')
        ]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

    }
}
