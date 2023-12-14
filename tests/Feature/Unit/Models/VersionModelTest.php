<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\Dev\IRK\VersionModel;

class VersionModelTest extends TestCase
{
    public function testShowDataAppVersion()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->table->select->where->get')->andReturn([['os_type' => 'mocked_os_type', 'version_code' => 'mocked_version_code', 'version_name' => 'mocked_version_name']]);

        $request = [
            'os_type' => 'some_os_type',
            // Add other required keys for the method
        ];

        $result = VersionModel::showDataAppVersion($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertEquals(['os_type' => 'mocked_os_type', 'version_code' => 'mocked_version_code', 'version_name' => 'mocked_version_name'], $result['data']);
    }

    public function testInputDataAppVersion()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        $request = [
            'os_type' => 'some_os_type',
            'version_code' => 'some_version_code',
            'version_name' => 'some_version_name',
            // Add other required keys for the method
        ];

        $result = VersionModel::inputDataAppVersion($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertTrue($result['data']);
    }
}
