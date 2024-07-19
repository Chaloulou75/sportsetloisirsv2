<?php

namespace Tests\Feature\Structures;

use App\Models\Structure;
use App\Models\User;
use App\Models\City;
use App\Models\Famille;
use App\Models\ListDiscipline;
use App\Models\Structuretype;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class ShowStructureTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $structure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->structure = Structure::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function show_method_returns_correct_inertia_response()
    {
        $response = $this->get(route('structures.show', $this->structure));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Structures/Show')
            ->has('structure')
            ->has('familles')
            ->has('allCities')
            ->has('listDisciplines')
            ->has('criteres')
            ->has('can')
            ->has('allStructureTypes')
            ->has('filters')
            ->has('currentRoute')
        );
    }

    /** @test */
    public function show_method_loads_correct_structure()
    {
        $response = $this->get(route('structures.show', $this->structure));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->where('structure.id', $this->structure->id)
            ->where('structure.name', $this->structure->name)
        );
    }

    /** @test */
    public function show_method_increments_view_count()
    {
        $initialViewCount = $this->structure->view_count;

        $this->get(route('structures.show', $this->structure));

        $this->structure->refresh();
        $this->assertEquals($initialViewCount + 1, $this->structure->view_count);
    }

    /** @test */
    public function show_method_applies_filters_correctly()
    {
        $filters = ['crit' => ['some_criteria'], 'ssCrit' => ['some_sub_criteria']];

        $response = $this->get(route('structures.show', $this->structure) . '?' . http_build_query($filters));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->where('filters', $filters)
        );
    }

    /** @test */
    public function show_method_loads_related_models()
    {
        $response = $this->get(route('structures.show', $this->structure));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->has('structure.creator')
            ->has('structure.users')
            ->has('structure.adresses')
            ->has('structure.departement')
            ->has('structure.structuretype')
            ->has('structure.disciplines')
        );
    }

    /** @test */
    public function show_method_sets_correct_permissions()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('structures.show', $this->structure));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->where('can.update', true)
            ->where('can.delete', true)
        );
    }

    /** @test */
    public function show_method_loads_cached_data()
    {
        Cache::shouldReceive('remember')
            ->times(3)
            ->andReturn(collect());

        $this->get(route('structures.show', $this->structure));
    }

    /** @test */
    public function show_method_returns_correct_current_route()
    {
        $response = $this->get(route('structures.show', $this->structure));

        $response->assertInertia(
            fn (Assert $page) => $page
            ->where('currentRoute.name', 'structures.show')
            ->where('currentRoute.params.structure', $this->structure)
        );
    }
}
