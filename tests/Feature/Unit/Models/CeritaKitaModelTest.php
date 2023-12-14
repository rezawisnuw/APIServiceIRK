<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Mockery;

use App\Models\Dev\IRK\CeritakitaModel;

class CeritaKitaModelTest extends TestCase
{
    use RefreshDatabase;


    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testShowDataCeritakita()
    {
        // Mocking request data
        $request = [
            'userid' => 1,
            'page' => 1,
        ];

        // Mocking CeritakitaModel
        $ceritakitaModelMock = Mockery::mock('alias:App\Models\Dev\IRK\CeritakitaModel');
        $ceritakitaModelMock->shouldReceive('showDataCeritakita')->with($request)->andReturn([
            'status' => 'Success',
            'data' => [
                (object)['idticket' => 1, 'title' => 'Cerita 1', 'created' => '2023-01-01 12:00:00', 'alias' => 'SomeAlias'],
                (object)['idticket' => 2, 'title' => 'Cerita 2', 'created' => '2023-01-02 12:00:00', 'alias' => 'AnotherAlias'],
            ],
            'message' => 'Data has been process',
        ]);

        // Call the method from CeritakitaModel
        $result = $ceritakitaModelMock::showDataCeritakita($request);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals(2, count($result['data']));
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertEquals('SomeAlias', $result['data'][0]->alias);
    }

    public function testShowDataCeritakitaTotal()
    {
        // Mocking CeritakitaModel
        $ceritakitaModelMock = Mockery::mock('alias:App\Models\Dev\IRK\CeritakitaModel');
        $ceritakitaModelMock->shouldReceive('showDataCeritakitaTotal')->andReturn([
            'status' => 'Success',
            'data' => 1,
            'message' => 'Data has been process',
        ]);

        // Call the method from CeritakitaModel
        $result = $ceritakitaModelMock::showDataCeritakitaTotal([]);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals(1, (int)$result['data']);
        $this->assertEquals('Data has been process', $result['message']);
    }

}
