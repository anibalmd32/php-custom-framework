<?php

namespace App\attributes;

use Attribute;
use App\enums\HTTP_METHODS;

#[Attribute(Attribute::TARGET_METHOD)]
final class Route
{
    public function __construct(
        public HTTP_METHODS $method,
        public string $path,
    ) {
    }
}
