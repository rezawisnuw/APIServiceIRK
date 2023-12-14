<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Dev\IRK\PresensiController;
use Illuminate\Http\Request;
use Tests\TestCase;

class IRKPresensiControllerTest extends TestCase
{
    public function testGet()
    {
        // Lakukan pengujian permintaan ke endpoint controller PresensiController
        $response = $this->json('POST', '/api/dev/presensi/get', ['userid' => '2005004059']);

        // Lakukan pengujian pada respons
        $response->assertStatus(200); // Memeriksa apakah status respons adalah 200 OK
        $response->assertJson(['status' => 'Success']); // Memeriksa apakah status dalam JSON adalah "Success"
    }
}
