<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is regular User
     */
    public function isRegularUser(): bool
    {
        return $this->hasRole('user');
    }

    /**
     * Check if user can assign roles to another user
     * Super Admin can assign to everyone
     * Admin can assign to regular users only
     * User cannot assign roles
     */
    public function canAssignRoleTo(User $targetUser): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super Admin can assign to anyone
        }

        if ($this->isAdmin()) {
            // Admin can assign to regular users only, not to other admins or super-admin
            return $targetUser->isRegularUser();
        }

        return false; // Regular users cannot assign roles
    }

    /**
     * Check if user can manage admin access (Super Admin only)
     */
    public function canManageAdminAccess(): bool
    {
        return $this->isSuperAdmin() && $this->can('manage-admin-access');
    }

    /**
     * Check if user can manage user access (Super Admin and Admin)
     */
    public function canManageUserAccess(): bool
    {
        return ($this->isSuperAdmin() || $this->isAdmin()) && $this->can('manage-user-access');
    }

    /**
     * Get roles that current user can assign to others
     */
    public function getAssignableRoles(): array
    {
        if ($this->isSuperAdmin()) {
            return ['admin', 'user']; // Super Admin can assign admin and user roles
        }

        if ($this->isAdmin()) {
            return ['user']; // Admin can only assign user role
        }

        return []; // Regular users cannot assign roles
    }

    /**
     * Check if user has permission
     */
    public function can($permission, $guardName = null): bool
    {
        return parent::can($permission, $guardName);
    }
}