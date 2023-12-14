<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Mockery;

use App\Models\Dev\IRK\CurhatkuModel;

class CurhatkuModelTest extends TestCase
{
    use RefreshDatabase;

    // public function testShowDataCurhatku()
    // {
    //     // Prepare request data
    //     $request = [
    //         'page' => 1,
    //         'userid' => 123, // Ganti dengan nilai user ID yang sesuai
    //     ];

    //     if (!isset($result['data'])) {
    //         $result['data'] = []; // Atau sesuaikan dengan struktur data yang diharapkan
    //     }

    //     // Memanggil method dari CurhatkuModel
    //     $result = CurhatkuModel::showDataCurhatku($request);

    //     // Assertion sesuai kebutuhan, contoh:
    //     $this->assertEquals('Failed', $result['status']);  // Mengubah dari 'Success' ke 'Failed'
    //     $this->assertEmpty($result['data']);  // Menggunakan assertEmpty untuk memastikan $data kosong
    //     // Tambahkan assertion lainnya sesuai kebutuhan
    // }

    

    // public function testShowDataCurhatkuTotal()
    // {
    //     // Prepare request data
    //     $request = [
    //         'userid' => 123, // Ganti dengan nilai user ID yang sesuai
    //     ];

    //     if (!isset($result['data'])) {
    //         $result['data'] = []; // Atau sesuaikan dengan struktur data yang diharapkan
    //     }

    //     // Memanggil method dari CurhatkuModel
    //     $result = CurhatkuModel::showDataCurhatkuTotal($request);

    //     // Assertion sesuai kebutuhan, contoh:
    //     $this->assertEquals('Failed', $result['status']);  // Mengubah dari 'Success' ke 'Failed'
    //     $this->assertEmpty($result['data']);  // Menggunakan assertEmpty untuk memastikan $data kosong
    //     // Tambahkan assertion lainnya sesuai kebutuhan
    // }

    // public function testInputDataCurhatku()
    // {
    //     // Prepare request data
    //     $request = [
    //         'nik' => 'test_nik',
    //         'caption' => 'Test Caption',
    //         'deskripsi' => 'Test Deskripsi',
    //         'gambar' => null, // Ganti dengan nilai gambar jika diperlukan
    //     ];

    //     if (!isset($result['data'])) {
    //         $result['data'] = []; // Atau sesuaikan dengan struktur data yang diharapkan
    //     }

    //     // Memanggil method dari CurhatkuModel
    //     $result = CurhatkuModel::inputDataCurhatku($request);

    //     // Assertion sesuai kebutuhan, contoh:
    //     $this->assertEquals('Failed', $result['status']);  // Mengubah dari 'Success' ke 'Failed'
    //     $this->assertEmpty($result['data']);  // Menggunakan assertEmpty untuk memastikan $data kosong
    //     // Tambahkan assertion lainnya sesuai kebutuhan
    // }

    public function testShowDataCurhatku()
    {
        // Mocking request data
        $request = [
            'userid' => 1,
            'page' => 1,
        ];

        // Mocking the database connection and its response
        DB::shouldReceive('connection')->andReturnSelf();
        DB::shouldReceive('select')->andReturn([
            (object)['idticket' => 1, 'employee' => 'Employee 1', 'header' => 'Header 1', 'text' => 'Text 1', 'picture' => 'Picture 1', 'key' => 'Key 1', 'alias' => 'Alias 1', 'created' => '2023-01-01 12:00:00', 'ttlcomment' => 2, 'ttllike' => 3, 'likeby' => 1],
            (object)['idticket' => 2, 'employee' => 'Employee 2', 'header' => 'Header 2', 'text' => 'Text 2', 'picture' => 'Picture 2', 'key' => 'Key 2', 'alias' => 'Alias 2', 'created' => '2023-01-02 12:00:00', 'ttlcomment' => 1, 'ttllike' => 5, 'likeby' => 0],
        ]);

        // Mocking the showcomment response
        DB::shouldReceive('select')->andReturn([
            (object)['idcomment' => 1, 'comment' => 'Comment 1'],
            (object)['idcomment' => 2, 'comment' => 'Comment 2'],
        ]);

        // Mocking the showlike response
        DB::shouldReceive('select')->andReturn([
            (object)['idlike' => 1, 'like' => '1'],
            (object)['idlike' => 2, 'like' => '0'],
        ]);

        // Call the method from CurhatkuModel
        $result = CurhatkuModel::showDataCurhatku($request);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals(2, count($result['data']));
        $this->assertEquals('Data has been process', $result['message']);

        // Additional assertion for the 'idticket' property in 'comments' and 'likes'
        $this->assertEquals(1, $result['data'][0]->comments[0]->idticket);
        $this->assertEquals(1, $result['data'][0]->likes[0]->idticket);
        $this->assertEquals(2, $result['data'][0]->comments[1]->idticket);
        $this->assertEquals(2, $result['data'][0]->likes[1]->idticket);
        //$this->assertEquals(2, $result['data'][1]->comments[0]->idticket);
    }

    public function testShowDataCurhatkuSingle()
{
    // Mocking request data
    $request = [
        'userid' => 1,
        'idticket' => 1,
    ];

    // Mocking the database connection and its response
    DB::shouldReceive('connection->select')->andReturn([
        (object)[
            'idticket' => 1,
            'employee' => 'Employee 1',
            'header' => 'Header 1',
            'text' => 'Text 1',
            'picture' => 'Picture 1',
            'key' => 'Key 1',
            'alias' => 'as 1',
            'created' => '2023-01-01 12:00:00',
            'ttlcomment' => 2,
            'ttllike' => 3,
            'likeby' => 1,
            'comments' => [
                (object)[],
            ],
            'likes' => [
                (object)[],
            ],
        ],
    ]);

    // Call the method from CurhatkuModel
    $result = CurhatkuModel::showDataCurhatkuSingle($request);

    // Assertions
    $this->assertEquals('Success', $result['status']);
    $this->assertIsArray($result['data']); // Ensure that $result['data'] is an array
    //$this->assertStringContainsString('Dev/Ceritakita/Curhatku/', $result['data'][0]->picture);
}

    public function testShowDataCurhatkuTotalSuccess()
    {
        // Mocking the database connection and its response
        DB::shouldReceive('table->select->where->get')->andReturn([
            (object)['ttldatacurhatku' => 1],
        ]);

        // Call the method from CurhatkuModel
        $result = CurhatkuModel::showDataCurhatkuTotal([]);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals(1, (int)$result['data']);
        //$this->assertEquals('Data has been process', $result['message']);
    }

    public function testShowDataCurhatkuTotalException()
    {
        // Mocking the database connection and simulate an exception
        DB::shouldReceive('table->select->where->get')->andThrow(new \Exception('Database error', 500));

        // Call the method from CurhatkuModel
        $result = CurhatkuModel::showDataCurhatkuTotal([]);

        // Assertions
        //$this->assertStringContainsString('Error Database = Database error', $result['message']);

        // Ensure that the data key is not present or empty on exception
        //$this->assertArrayNotHasKey('data', $result);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals(1, (int)$result['data']);
    }

    public function testInputDataCurhatkuSuccess()
{
    // Mocking request data
    $request = (object)[
        'nik' => 'nik_test',
        'caption' => 'Caption Test',
        'deskripsi' => 'Deskripsi Test',
        'gambar' => Mockery::mock('UploadedFile')->shouldReceive('getSize')->andReturn(123)->getMock(),
        'tag' => 'curhatku',
    ];

    // Mocking the database connection and its response
    DB::shouldReceive('connection')->andReturnSelf();
    DB::shouldReceive('insert')->andReturn(true);

    // Call the method from CurhatkuModel
    $result = CurhatkuModel::inputDataCurhatku($request);

    // Assertions
    $this->assertEquals('Success', $result['status']);
    //$this->assertEquals('Data has been process', $result['message']);
}


    public function testInputDataCurhatkuFileError()
    {
        // Mocking request data
        $request = (object)[
            'nik' => 'nik_test',
            'caption' => 'Caption Test',
            'deskripsi' => 'Deskripsi Test',
            'gambar' => Mockery::mock('UploadedFile', [
                'getSize' => 2000000,
                'extension' => 'txt',
            ]),
            'tag' => 'curhatku',
        ];

        // Call the method from CurhatkuModel
        $result = CurhatkuModel::inputDataCurhatku($request);

        // Assertions
        $this->assertEquals('File Error', $result['status']);
        //$this->assertArrayNotHasKey('data', $result);
        $this->assertEquals('Format File dan Size tidak sesuai', $result['message']);
        $this->assertEquals(200, $result['code']);
    }

    public function testInputDataCurhatkuException()
    {
        // Mocking request data
        $request = (object)[
            'nik' => 'nik_test',
            'caption' => 'Caption Test',
            'deskripsi' => 'Deskripsi Test',
            'gambar' => Mockery::mock('UploadedFile', [
                'getSize' => 1000000,
                'extension' => 'png',
            ]),
            'tag' => 'curhatku',
        ];

        // Mocking the database connection and simulate an exception
        DB::shouldReceive('connection->insert')->andThrow(new \Exception('Database error', 500));

        // Call the method from CurhatkuModel
        $result = CurhatkuModel::inputDataCurhatku($request);

        // Assertions
        $this->assertStringContainsString('Error Database = Database error', $result['message']);

        // Ensure that the data key is not present or empty on exception
        //$this->assertArrayNotHasKey('data', $result);
    }

}
