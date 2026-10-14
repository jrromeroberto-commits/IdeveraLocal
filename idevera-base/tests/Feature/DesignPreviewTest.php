<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DesignPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_prototype_is_available_without_accessing_private_project_data(): void
    {
        $this->get(route('design.project'))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('design/project')->missing('project')->where('auth.user', null));
    }

    public function test_prototype_is_unavailable_in_production_and_home_hides_its_link(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $this->get(route('design.project'))->assertNotFound();
        $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('welcome')->where('designPreview', false));
    }
}
