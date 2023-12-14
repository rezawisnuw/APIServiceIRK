<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Dev\AuthModel;

class AuthModelTest extends TestCase
{
    // public function testLogin()
    // {
    //     $request = [
    //         'nik' => 'your_nik',
    //         'password' => 'your_password',
    //     ];

    //     $result = AuthModel::Login($request);

    //     $this->assertEquals('Failed', $result['status']);
    //     $this->assertNotNull($result['data']);
    //     $this->assertEquals('Data has been process', $result['message']);
    // }

    // public function testLogout()
    // {
    //     // Simulate a request object, you may need to adjust it based on your actual implementation
    //     $request = new \Illuminate\Http\Request();

    //     // Set a sample token in the request cookie, you may need to adjust it based on your actual implementation
    //     $request->headers->add(['cookie' => 'Authorization-dev=Bearer your_sample_token']);

    //     $result = AuthModel::Logout($request);

    //     $this->assertEquals('Failed', $result['status']);
    //     $this->assertNotNull($result['data']);
    //     $this->assertEquals('Data has been process', $result['message']);
    // }

    public function testAuthenticate()
    {
        // Simulate a request object, you may need to adjust it based on your actual implementation
        $request = new \Illuminate\Http\Request();

        // Set a sample token in the request cookie, you may need to adjust it based on your actual implementation
        $request->headers->add(['cookie' => 'Authorization-dev=Bearer your_sample_token']);

        $result = AuthModel::Authenticate($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertNotNull($result['data']);
        $this->assertEquals('Data has been process', $result['message']);
    }
}
