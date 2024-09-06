<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Blog;

class PostPolicy
{
    public function view(User $user, Blog $post)
    {
        return true; 
    }

    public function update(User $user, Blog $post)
    {
        return $user->id === $post->user_id || $user->hasRole('sysadmin') || $user->hasRole('manager');
    }

    public function delete(User $user, Blog $post)
    {
        return $user->id === $post->user_id || $user->hasRole('sysadmin') || $user->hasRole('manager');
    }

    public function approve(User $user, Blog $post)
    {
        return $user->hasRole('sysadmin') || $user->hasRole('manager');
    }

    public function access(User $user, Blog $post)
    {
        if($user->id === $post->user_id || $user->hasRole('sysadmin') || $user->hasRole('manager')) return true;
        if($post->status === 'public' && $post->is_approved == true) return true;
        return false; 
    }

}