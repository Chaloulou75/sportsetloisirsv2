<?php

use function Pest\Laravel\Get;

it('test the application returns a successful response for home page', function () {
    //Act & Assert
    get(route('welcome'))->assertOk();
});
