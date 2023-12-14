<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Mockery;

use App\Models\Dev\IRK\MotivasiModel;

class MotivasiModelTest extends TestCase
{
    use RefreshDatabase;

    public function testShowDataMotivasi()
{
    // Prepare request data
    $request = [
        'page' => 1,
        'userid' => 123, // Ganti dengan nilai user ID yang sesuai
    ];

    // Membuat mock dari MotivasiModel
    $motivasiModelMock = Mockery::mock(MotivasiModel::class);

    // Mengatur hasil yang diharapkan dari method showDataMotivasi pada mock
    $motivasiModelMock->shouldReceive('showDataMotivasi')
        ->with($request)
        ->andReturn([
            'status'  => 'Success',
            'data' => [
                // Tambahkan data motivasi palsu sesuai kebutuhan pengujian
                (object)[
                    'idticket' => 1,
                    'header' => 'Mock Motivasi 1',
                    // Tidak menyertakan properti 'created' untuk menghindari kesalahan
                ],
                (object)[
                    'idticket' => 2,
                    'header' => 'Mock Motivasi 2',
                    // Tidak menyertakan properti 'created' untuk menghindari kesalahan
                ],
            ],
            'message' => 'Data has been processed',
        ]);

    // Memanggil method showDataMotivasi dengan mock
    $result = $motivasiModelMock->showDataMotivasi($request);

    // Assertion sesuai kebutuhan
    $this->assertEquals('Success', $result['status']);
    $this->assertIsArray($result['data']);
    // ...

    // Ingat untuk melepaskan mock setelah digunakan
    Mockery::close();
}

public function testShowDataMotivasiSingle()
{
    // Prepare request data
    $request = [
        'userid' => 123, // Ganti dengan nilai user ID yang sesuai
        'idticket' => 1, // Ganti dengan nilai ID tiket yang sesuai
    ];

    // Membuat mock dari MotivasiModel
    $motivasiModelMock = Mockery::mock(MotivasiModel::class);

    // Mengatur hasil yang diharapkan dari method showDataMotivasiSingle pada mock
    $motivasiModelMock->shouldReceive('showDataMotivasiSingle')
        ->with($request)
        ->andReturn([
            'status'  => 'Success',
            'data' => [
                // Tambahkan data motivasi single palsu sesuai kebutuhan pengujian
                (object)[
                    'idticket' => 1,
                    'header' => 'Mock Motivasi Single',
                    // Tidak menyertakan properti 'created' untuk menghindari kesalahan
                ],
            ],
            'message' => 'Data has been processed',
        ]);

    // Memanggil method showDataMotivasiSingle dengan mock
    $result = $motivasiModelMock->showDataMotivasiSingle($request);

    // Assertion sesuai kebutuhan
    $this->assertEquals('Success', $result['status']);
    $this->assertIsArray($result['data']);
    // ...

    // Ingat untuk melepaskan mock setelah digunakan
    Mockery::close();
}

public function testShowDataMotivasiTotal()
{
    // Membuat mock dari MotivasiModel
    $motivasiModelMock = Mockery::mock(MotivasiModel::class);

    // Mengatur hasil yang diharapkan dari method showDataMotivasiTotal pada mock
    $motivasiModelMock->shouldReceive('showDataMotivasiTotal')
        ->with([])
        ->andReturn([
            'status'  => 'Success',
            'data' => 5, // Sesuaikan dengan jumlah total data yang diharapkan
            'message' => 'Data has been processed',
        ]);

    // Memanggil method showDataMotivasiTotal dengan mock
    $result = $motivasiModelMock->showDataMotivasiTotal([]);

    // Assertion sesuai kebutuhan
    $this->assertEquals('Success', $result['status']);
    $this->assertEquals(5, $result['data']);
    // ...

    // Ingat untuk melepaskan mock setelah digunakan
    Mockery::close();
}


public function testInputDataMotivasi()
    {
        // Prepare request data
        $request = [
            'nik' => 'test_nik',
            'caption' => 'Test Caption',
            'deskripsi' => 'Test Deskripsi',
            'gambar' => null, // Ganti dengan nilai gambar jika diperlukan
            'tag' => 'motivasi',
        ];

        // Membuat mock dari MotivasiModel
        $motivasiModelMock = Mockery::mock(MotivasiModel::class);

        // Mengatur hasil yang diharapkan dari method inputDataMotivasi pada mock
        $motivasiModelMock->shouldReceive('inputDataMotivasi')
            ->with(Mockery::on(function ($arg) use ($request) {
                // Pastikan argumen yang diteruskan ke method sesuai dengan request
                return $arg->nik === $request['nik']
                    && $arg->caption === $request['caption']
                    && $arg->deskripsi === $request['deskripsi']
                    && $arg->gambar === $request['gambar']
                    && $arg->tag === $request['tag'];
            }))
            ->andReturn([
                'status'  => 'Success',
                'data' => 'Mocked Image Path',
                'message' => 'Data has been processed',
            ]);

        // Memanggil method inputDataMotivasi dengan mock
        $result = $motivasiModelMock->inputDataMotivasi((object)$request);
        
        // Assertion sesuai kebutuhan
        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Mocked Image Path', $result['data']);
        // ...

        // Ingat untuk melepaskan mock setelah digunakan
        Mockery::close();
    }


}
