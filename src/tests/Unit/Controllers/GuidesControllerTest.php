<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\GuidesController;
use Illuminate\Http\Request;
use Mockery;
use App\Models\Guide;

class GuidesControllerTest extends TestCase
{
    public function testIndexMethodWithFilter()
    {
        // Создаем мок запроса
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->with('min_experience')
            ->andReturn(5);

        // Создаем мок модели
        $guideMock = Mockery::mock(Guide::class);
        $guideMock->shouldReceive('query')
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('experience_years', '>=', 5)
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn([]);

        $controller = new GuidesController();
        $result = $controller->index($request);

        // Проверяем результат
        $this->assertInstanceOf(\Illuminate\View\View::class, $result);
    }
}
