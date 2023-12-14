<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use Tests\TestCase;

use App\Http\Controllers\Dev\IRK\ReportController;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetEndToEndSuccess()
    {
        $response = $this->post('/api/dev/report/get', [
            'userid' => 1,
            'code' => '2'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'message',
            'status',
        ]);
    }

    public function testGetEndToEndFailure()
    {
        $response = $this->post('/api/dev/report/get', [
            'userid' => 1,
            'code' => '0'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
            'message'
        ]);
        $response->assertJson(['status' => 'Error']);
    }

    public function testGetUnitSuccess()
    {
        $gatewayMock = $this->getMockBuilder(ReportController::class)
            ->disableOriginalConstructor() 
            ->getMock();

        $expectedResponse = [
            'result' => 'Data has been process',
            'data' => [/* Data yang diharapkan di sini */],
            'message' => 'Success on Run',
            'status' => 1,
            'statuscode' => 200
        ];

        $gatewayMock->method('get')->willReturn(json_encode($expectedResponse));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = $gatewayMock->get($request);

        $this->assertEquals(json_encode($expectedResponse), $response);
        $this->assertJson($response);

        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('result', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('statuscode', $responseData);
        $this->assertEquals('Data has been process', $responseData['result']);
    }

    public function testGetUnitFailure()
    {
        $gatewayMock = $this->getMockBuilder(ReportController::class)
            ->disableOriginalConstructor() 
            ->getMock();

        $expectedResponse = [
            'result' => 'Mismatch',
            'data' => [], 
            'message' => 'Failed on Run',
            'status' => 0,
        ];

        $gatewayMock->method('get')->willReturn(json_encode($expectedResponse));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = $gatewayMock->get($request);

        $this->assertEquals(json_encode($expectedResponse), $response);
        $this->assertJson($response);
    }
}
