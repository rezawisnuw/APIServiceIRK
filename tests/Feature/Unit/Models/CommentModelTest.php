<?php

namespace Tests\Feature\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\Dev\IRK\CommentModel;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    public function testShowDataCommentTotalSuccess()
    {
        // Mocking the database connection and its response
        DB::shouldReceive('connection->select')->andReturn([
            (object)['id_comment' => 1, 'comment' => 'Comment 1'],
            (object)['id_comment' => 2, 'comment' => 'Comment 2'],
        ]);

        // Call the method from CommentModel
        $result = CommentModel::showDataCommentTotal(['idticket' => 1]);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertCount(2, $result['data']);
        $this->assertEquals('Data has been process', $result['message']);
    }

    public function testShowDataCommentTotalException()
    {
        // Mocking the database connection and simulate an exception
        DB::shouldReceive('connection->select')->andThrow(new \Exception('Database error', 500));

        // Call the method from CommentModel
        $result = CommentModel::showDataCommentTotal(['idticket' => 1]);

        // Assertions
        $this->assertEquals('Error Database = Database error', $result['message']);

        // Ensure that the data key is not present or empty on exception
        $this->assertArrayHasKey('data', $result);
        $this->assertNotNull($result['data']);
    }

    public function testInputDataCommentSuccess()
    {
        // Mocking the database connection and its response
        DB::shouldReceive('connection->insert')->andReturn(true);

        // Call the method from CommentModel
        $result = CommentModel::inputDataComment([
            'nik' => '123',
            'comment' => 'Test comment',
            'idticket' => 1,
        ]);

        // Assertions
        $this->assertEquals('Success', $result['status']);
        $this->assertTrue($result['data']);
        $this->assertEquals('Data has been process', $result['message']);
    }

    public function testInputDataCommentException()
    {
        // Mocking the database connection and simulate an exception
        DB::shouldReceive('connection->insert')->andThrow(new \Exception('Database error', 500));

        // Call the method from CommentModel
        $result = CommentModel::inputDataComment([
            'nik' => '123',
            'comment' => 'Test comment',
            'idticket' => 1,
        ]);

        // Assertions
        $this->assertEquals('Error Database = Database error', $result['message']);

        // Ensure that the data key is not present or empty on exception
        $this->assertArrayHasKey('data', $result);
        $this->assertNotNull($result['data']);
    }
}
