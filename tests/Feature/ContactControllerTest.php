<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contacts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase; // Rollback database changes after each test

    /** @test to get list of contacts */
    public function it_retrieves_all_contacts_with_pagination()
    {
        $user = User::factory()->create();
        Contacts::factory()->count(15)->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/contacts?per_page=5');

        $response->assertStatus(200);
    }

    /** @test to create a new contact */
    public function it_creates_a_new_contact()
    {
        // create a fake user
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // insert a contact under that fake user
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/contacts', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '1234567890'
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['status_code', 'results' => ['message']]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'user_id' => $user->id,
        ]);
    }

    /** @test to get a specific contact by id */
    public function it_retrieves_a_specific_contact()
    {
        // create a fake user
        $user = User::factory()->create();
        $contact = Contacts::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['status_code', 'results' => ['id', 'user_id', 'name', 'email', 'phone', 'created_at', 'updated_at']]);
    }

    /** @test to delete a contact by id */
    public function it_deletes_a_contact()
    {
        // create a fake user
        $user = User::factory()->create();
        $contact = Contacts::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['status_code', 'results' => ['message']]);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test to export contacts in csv */
    public function it_exports_contacts_as_csv()
    {
        // create a fake user
        $user = User::factory()->create();
        Contacts::factory()->count(5)->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        // create a fake excel
        Excel::fake();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/contacts/csv/export');

        $response->assertStatus(200);

        Excel::assertDownloaded('contacts.xlsx', function ($export) use ($user) {
            // Check if 5 contacts are exported
            return $export->collection()->count() === 5;
        });
    }

    /** @test */
    public function it_imports_contacts_from_csv()
    {
        // create a fake user
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // create a fake excel data
        Excel::fake();

        $file = UploadedFile::fake()->create('contacts.csv', 100, 'text/csv');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/contacts/csv/import', [
                'file' => $file,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['status_code', 'results' => ['message']]);

        // import the fake excel into database
        Excel::assertImported('contacts.csv');
    }
}
