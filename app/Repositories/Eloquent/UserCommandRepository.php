<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserCommandRepositoryInterface;

class UserCommandRepository implements UserCommandRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }
    
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        
        return $user->fresh();
    }
    
    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        
        return $user->delete();
    }
}