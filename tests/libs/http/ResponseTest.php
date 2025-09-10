<?php

namespace Tests\libs\http;

use App\libs\http\Response;

beforeEach(function () {
    $_SERVER = [];
    $_GET    = [];
    $_POST   = [];
    $_FILES  = [];
});

describe('Response Lib', function () {
    // No se puede testear en un entorno CLI
});
