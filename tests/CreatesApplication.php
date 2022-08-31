<?php

declare(strict_types=1);


namespace Tests;


trait CreatesApplication
{
    protected function setUp(): void
    {
        $this->boot();
    }

    public function boot()
    {
        require __DIR__.'/../index.php';
    }
}