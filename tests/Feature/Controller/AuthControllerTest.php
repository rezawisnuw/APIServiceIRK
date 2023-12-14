<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use Tests\TestCase;

use App\Http\Controllers\Dev\AuthController;
use App\Models\Dev\AuthModel; 

class AuthControllerTest extends TestCase
{
    public function testAuthentication() 
    { 
        $request = new Request(); 
        $request->data = [ 'code' => 1, 'nik' => '123123', 'password' => 'password', ]; 
        
        $authModelMock = $this->createMock(AuthModel::class); 
        $authController = new AuthController($authModelMock); 
        
        $response = $authController->Authentication($request); 
        $this->assertEquals(200, $response->getStatusCode());
    } 
}
