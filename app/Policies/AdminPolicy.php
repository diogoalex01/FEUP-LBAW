<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class AdminPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the admin can view any models.
     *
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function viewAny(Admin $admin)
    {
        //
    }

    /**
     * Determine whether the admin can view the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function view(Admin $admin)
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the admin can create models.
     *
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function create(Admin $admin)
    {
        //
    }

    /**
     * Determine whether the admin can update the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function update(Admin $admin)
    {
        return Auth::check();
    }

    /**
     * Determine whether the admin can delete the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function delete(Admin $admin, Admin $model)
    {
        return $admin->id == $model->id;
    }

    /**
     * Determine whether the admin can restore the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function restore(Admin $admin, Admin $model)
    {
        //
    }

    /**
     * Determine whether the admin can permanently delete the model.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Admin  $model
     * @return mixed
     */
    public function forceDelete(Admin $admin, Admin $model)
    {
        //
    }
}
