<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static $storage;
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function seed()
    {
        $this->artisan('db:seed');

        self::$storage['contas']['_credencial'] = '2e1206a83983f29d741493d2339f59d0d67f6bbb';
        self::$storage['contas']['_chave'] = '5f9d858b17cbf27568bfc7b8e20c5e6097370a81';

        self::$storage['bancos']['_principal'] = 'ca578063-42e9-426b-8b80-2cffd34a8387';
    }
}
