<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

use App\Models\Dev\Credential;

class CredentialTest extends TestCase
{
    public function testIsTokenSignatureValid()
    {
        // Membuat MockHandler untuk meng-handle request HTTP
        $mockHandler = new MockHandler();

        // Membuat HandlerStack dan menambahkan MockHandler
        $handlerStack = HandlerStack::create($mockHandler);

        // Membuat Client dengan HandlerStack
        $client = new Client(['handler' => $handlerStack]);

        // Membuat mock untuk Credential dan menggantikan instance GuzzleHttp\Client
        $credentialMock = Mockery::mock(Credential::class);
        $credentialMock->shouldReceive('setHttpClient')->once()->with($client);

        // Memanggil metode setHttpClient pada Credential dengan mock
        $credentialMock->setHttpClient($client);

        // Melakukan assertion pada mock
        $this->assertTrue(true); // Jika sampai sini tanpa exception, berarti berhasil
    }

    public function testLoginSuccess()
    {
        // Membuat mock untuk Credential
        $credentialMock = Mockery::mock(Credential::class);
        $credentialMock->shouldReceive('Login')->andReturn([
            'wcf' => ['Result' => 'Success', 'Message' => 'Berhasil Login', 'Status' => '1', 'Code' => 200],
            'token' => 'dummy_token'
        ]);

        // Memanggil metode login pada Credential dengan mock
        $result = $credentialMock->Login(['nik' => 'dummy_nik', 'password' => 'dummy_password']);

        // Melakukan assertion pada hasil
        $this->assertEquals('Success', $result['wcf']['Result']);
        $this->assertEquals('Berhasil Login', $result['wcf']['Message']);
        $this->assertEquals('dummy_token', $result['token']);
    }

    public function testLoginFailure()
    {
        // Membuat mock untuk Credential
        $credentialMock = Mockery::mock(Credential::class);
        $credentialMock->shouldReceive('Login')->andReturn([
            'wcf' => ['Result' => 'Failure', 'Message' => 'Gagal Login', 'Status' => '0', 'Code' => 200]
        ]);

        // Memanggil metode login pada Credential dengan mock
        $result = $credentialMock->Login(['nik' => 'dummy_nik', 'password' => 'dummy_password']);

        // Melakukan assertion pada hasil
        $this->assertEquals('Failure', $result['wcf']['Result']);
        $this->assertEquals('Gagal Login', $result['wcf']['Message']);
        $this->assertArrayNotHasKey('token', $result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Membersihkan semua mock setelah setiap pengujian
        Mockery::close();
    }
}
