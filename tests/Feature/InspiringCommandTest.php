<?php

test('link project command', function () {
    $this->artisan('link')
         ->expectsOutput('Simplicity is the ultimate sophistication.')
         ->assertExitCode(0);
});
