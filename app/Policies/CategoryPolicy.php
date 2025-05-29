<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function update(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    public function delete(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    public function restore(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    public function forceDelete(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }
}
