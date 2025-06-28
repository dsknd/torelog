<?php

namespace Tests\Feature;

use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class IntegrationTestCase extends BaseTestCase
{
    use DatabaseTransactions;
    
    protected function setUp(): void
    {
        parent::setUp();
    }
}