<?php

namespace App\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        return $this->where(['username' => $username], true);
    }

}
