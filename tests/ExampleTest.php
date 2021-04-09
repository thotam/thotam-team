<?php

namespace Thotam\ThotamTeam\Tests;

use Orchestra\Testbench\TestCase;
use Thotam\ThotamTeam\ThotamTeamServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ThotamTeamServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
