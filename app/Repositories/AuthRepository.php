<?php

namespace App\Repositories\Contracts;

interface AuthRepository
{
    public function authenticate ($data);
}
