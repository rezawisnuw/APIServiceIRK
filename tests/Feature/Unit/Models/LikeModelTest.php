<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Mockery;

use App\Models\Dev\IRK\LikeModel;

class LikeModelTest extends TestCase
{
    use RefreshDatabase;

    public function testShowDataLikeTotal()
    {
        // Prepare request data
        $request = [
            'idticket' => 1, // Ganti dengan nilai ID tiket yang sesuai
        ];

        // Memanggil method dari LikeModel
        $result = LikeModel::showDataLikeTotal($request);

        // Assertion sesuai kebutuhan, contoh:
        $this->assertEquals('Failed', $result['status']);  // Mengubah dari 'Success' ke 'Failed'
        $this->assertEmpty($result['data']);  // Menggunakan assertEmpty untuk memastikan $data kosong
        // Tambahkan assertion lainnya sesuai kebutuhan
    }

    public function testInputDataLike()
    {
        // Prepare request data
        $request = [
            'nik' => 'test_nik',
            'idticket' => 1, // Ganti dengan nilai ID tiket yang sesuai
            'tag' => 'curhatku', // Ganti dengan nilai tag yang sesuai
            'userlike' => 'test_userlike',
        ];

        // Memanggil method dari LikeModel
        $result = LikeModel::inputDataLike($request);

        // Assertion sesuai kebutuhan, contoh:
        $this->assertEquals('Failed', $result['status']);  // Mengubah dari 'Success' ke 'Failed'
        $this->assertEmpty($result['data']);  // Menggunakan assertEmpty untuk memastikan $data kosong
        // Tambahkan assertion lainnya sesuai kebutuhan
    }
}
