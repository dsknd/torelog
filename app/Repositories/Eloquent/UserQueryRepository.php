<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserQueryRepositoryInterface;

class UserQueryRepository implements UserQueryRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
    
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}