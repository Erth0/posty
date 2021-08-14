<?php

test('current folder already linked', function ($project) {
    $this->artisan('link')
        ->assertTrue(true)
         ->assertExitCode(0);
});
