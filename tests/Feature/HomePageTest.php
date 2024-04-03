<?php

use function Pest\Laravel\Get;

it('test the application returns a successful response for home page', function () {
    get(route('welcome'))->assertOk();
});
