<?php

namespace App\Policies;

use App\User;
use App\Community;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class CommunityPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any communitiess.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function view(User $user, Community $community)
    {
        //
    }

    /**
     * Determine whether the user can create communitys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function update(User $user, Community $community)
    {
        return $user->id === $community->id_author;
    }

    /**
     * Determine whether the user can delete the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function delete(User $user, Community $community)
    {
        //
    }

    /**
     * Determine whether the user can restore the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function restore(User $user, Community $community)
    {
        //
    }

    /**
     * Determine whether the user can report the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function report()
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can permanently delete the community.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function forceDelete(User $user, Community $community)
    {
        //
    }

    /**
     * Determine whether the admin can delete the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function adminDel()
    {
        return Auth::guard('admin')->check();
    }
}
