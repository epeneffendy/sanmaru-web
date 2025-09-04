<?php

namespace App\Policies;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any vouchers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Helper::isShopRole($user);
    }

    /**
     * Determine whether the user can view the voucher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function view(User $user, Voucher $voucher)
    {
        return Helper::isShopRole($user);
    }

    /**
     * Determine whether the user can create vouchers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Helper::isShopRole($user) && $user->type != User::SHOP;
    }

    /**
     * Determine whether the user can update the voucher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function update(User $user, Voucher $voucher)
    {
        return Helper::isShopRole($user) && $user->type != User::SHOP;
    }

    /**
     * Determine whether the user can delete the voucher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function delete(User $user, Voucher $voucher)
    {
        return Helper::isShopRole($user) && $user->type != User::SHOP;
    }

    /**
     * Determine whether the user can restore the voucher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function restore(User $user, Voucher $voucher)
    {
        return Helper::isShopRole($user) && $user->type != User::SHOP;
    }

    /**
     * Determine whether the user can permanently delete the voucher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function forceDelete(User $user, Voucher $voucher)
    {
        return Helper::isShopRole($user) && $user->type != User::SHOP;
    }
}
