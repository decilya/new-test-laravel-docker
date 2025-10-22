<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Guide;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuideFilterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Тест фильтрации гидов по минимальному опыту
     *
     * @return void
     */
    public function testFilterGuidesByMinExperience()
    {
        Guide::factory()->create([
            'experience_years' => 2
        ]);

        Guide::factory()->create([
            'experience_years' => 5
        ]);

        Guide::factory()->create([
            'experience_years' => 7
        ]);

        $response = $this->getJson(route('guides.index'), [
            'min_experience' => 5
        ]);

        $response->assertStatus(200);

        $response->assertJsonCount(2, 'data');

        // Проверяем, что все гиды соответствуют фильтру
        $response->assertJson(fn($json) => $json->each(function ($guide) {
            $this->assertGreaterThanOrEqual(5, $guide['experience_years']);
        }));
    }
}
