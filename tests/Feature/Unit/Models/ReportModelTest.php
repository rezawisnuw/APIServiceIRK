<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\Dev\IRK\ReportModel;

class ReportModelTest extends TestCase
{
    public function testShowDataReportTicket()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->table->orderBy->get')->andReturn(['mocked_data']);

        $request = [
            'some_key' => 'some_value',
            // Add other required keys for the method
        ];

        $result = ReportModel::showDataReportTicket($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertEquals(['mocked_data'], $result['data']);
    }

    public function testShowDataReportComment()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->table->orderBy->get')->andReturn(['mocked_data']);

        $request = [
            'some_key' => 'some_value',
            // Add other required keys for the method
        ];

        $result = ReportModel::showDataReportComment($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertEquals(['mocked_data'], $result['data']);
    }

    public function testInputDataReportTicket()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        $request = [
            'nik' => '12345',
            'report' => 'Some report',
            'idticket' => 'TICKET001',
            'tag' => 'important',
        ];

        $result = ReportModel::inputDataReportTicket($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertTrue($result['data']);
    }

    public function testInputDataReportComment()
    {
        // Mocking the Database Facade
        DB::shouldReceive('connection->insert')->andReturn(true);

        $request = [
            'nik' => '12345',
            'report' => 'Some comment',
            'idticket' => 'TICKET001',
            'tag' => 'feedback',
        ];

        $result = ReportModel::inputDataReportComment($request);

        $this->assertEquals('Success', $result['status']);
        $this->assertEquals('Data has been process', $result['message']);
        $this->assertTrue($result['data']);
    }
}
