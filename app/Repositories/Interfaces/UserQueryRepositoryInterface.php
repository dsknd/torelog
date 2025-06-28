<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserQueryRepositoryInterface
{
    public function findById(int $id): ?User;
    
    public function findByEmail(string $email): ?User;
}