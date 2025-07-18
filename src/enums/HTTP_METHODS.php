<?php

namespace App\enums;

enum HTTP_METHODS: string
{
    case GET    = 'GET';
    case POST   = 'POST';
    case PUT    = 'PUT';
    case DELETE = 'REMOVE';
    case PATCH  = 'PATCH';
}
