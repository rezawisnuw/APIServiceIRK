<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Models\Dev\IRK\ProfileModel;

class ProfileModelTest extends TestCase
{
    public function testShowDataProfile()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->table->where->get')->andReturn(['mocked_data']);

        $request = [
            'nik' => '12345',
        ];

        $result = ProfileModel::showDataProfile($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertEquals(['mocked_data'], $result['data']);
    }

    public function testInputDataProfileWithPhoto()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        // Mocking the UploadedFile
        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.jpg');

        $request = new \stdClass();
        $request->nik = '12345';
        $request->nama = 'User Test';
        $request->nohp = '123456789';
        $request->alias = 'u5312d03';
        $request->kelamin = 'male';
        $request->email = 'test@example.com';
        $request->photo = $file;

        $result = ProfileModel::inputDataProfile($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertStringContainsString('Dev/Ceritakita/Profile/12345_User Test.', $result['data']);
    }

    public function testInputDataProfileWithoutPhoto()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        $request = new \stdClass();
        $request->nik = '12345';
        $request->nama = 'User Test';
        $request->nohp = '123456789';
        $request->alias = 'u5312d03';
        $request->kelamin = 'male';
        $request->email = 'test@example.com';

        $result = ProfileModel::inputDataProfile($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertStringContainsString('Dev/Ceritakita/Profile/12345_User Test.', $result['data']);
    }

    public function testEditDataProfileWithPhoto()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        // Mocking the UploadedFile
        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.jpg');

        $request = new \stdClass();
        $request->nik = '12345';
        $request->nama = 'User Test';
        $request->nohp = '123456789';
        $request->alias = 'u5312d03';
        $request->kelamin = 'male';
        $request->email = 'test@example.com';
        $request->photo = $file;

        $result = ProfileModel::editDataProfile($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertStringContainsString('Dev/Ceritakita/Profile/12345_User Test.', $result['data']);
    }

    public function testEditDataProfileWithoutPhoto()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        $request = new \stdClass();
        $request->nik = '12345';
        $request->nama = 'User Test';
        $request->nohp = '123456789';
        $request->alias = 'u5312d03';
        $request->kelamin = 'male';
        $request->email = 'test@example.com';

        $result = ProfileModel::editDataProfile($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertStringContainsString('Dev/Ceritakita/Profile/12345_User Test.', $result['data']);
    }
}
