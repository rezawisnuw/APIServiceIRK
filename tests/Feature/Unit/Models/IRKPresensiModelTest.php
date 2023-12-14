<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Mockery;

use App\Models\Dev\IRK\PresensiModel;

class IRKPresensiModelTest extends TestCase
{
    use RefreshDatabase;

    public function testShowDataPresensi()
    {
        // Setup Guzzle Mock
        $mock = new MockHandler([
            new Response(200, [], json_encode(['status' => 'Success', 'message' => 'Data has been processed', 'data' => ['result' => 'mocked_data']])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        // Membuat mock dari PresensiModel
        $presensiModelMock = Mockery::mock(PresensiModel::class);

        // Mengatur hasil yang diharapkan dari method showDataPresensi pada mock
        $presensiModelMock->shouldReceive('setHttpClient')->with($guzzleClient)->andReturnNull();
        $presensiModelMock->shouldReceive('showDataPresensi')
            ->with('sample_user_id')
            ->andReturn([
                'status'  => 'Success',
                'message' => 'Data has been processed',
                'data'    => ['result' => 'mocked_data'],
            ]);

        // Memanggil method showDataPresensi dengan mock
        $result = $presensiModelMock->showDataPresensi('sample_user_id');
            
        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been processed', $result['message']);
        $this->assertNotNull($result['data']);
        $this->assertArrayHasKey('result', $result['data']);

        // Ingat untuk melepaskan mock setelah digunakan
        Mockery::close();
    }


}
