<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserCommandRepositoryInterface
{
    public function create(array $data): User;
    
    public function update(int $id, array $data): User;
    
    public function delete(int $id): bool;
}