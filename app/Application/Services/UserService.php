<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Identity\Entities\User;
use App\Domain\Identity\Events\UserCreatedEvent;
use App\Domain\Identity\Repositories\UserRepositoryInterface;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        // Validate company exists if provided
        if (!empty($data['company_id'])) {
            $company = $this->companyRepository->findById($data['company_id']);
            if (!$company) {
                throw new ModelNotFoundException("Company not found: {$data['company_id']}");
            }
        }

        $user = new User(
            id: Uuid::generate(),
            email: $data['email'],
            name: $data['name'],
            paternalSurname: $data['paternal_surname'] ?? null,
            maternalSurname: $data['maternal_surname'] ?? null,
            role: $data['role'] ?? 'employee',
            companyId: $data['company_id'] ?? null,
            siteId: $data['site_id'] ?? null,
            employeeId: $data['employee_id'] ?? null,
            department: $data['department'] ?? null,
            position: $data['position'] ?? null,
            phone: $data['phone'] ?? null,
            avatarUrl: $data['avatar_url'] ?? null,
            isActive: $data['is_active'] ?? true,
            emailVerifiedAt: !empty($data['email_verified']) ? now() : null
        );

        // Set password if provided
        if (!empty($data['password'])) {
            $user->setPassword($data['password']);
        }

        $this->userRepository->save($user);

        event(new UserCreatedEvent($user));

        return $user;
    }

    /**
     * Update user information
     */
    public function updateUser(string $userId, array $data): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        // Update profile
        $user->updateProfile([
            'name' => $data['name'] ?? $user->name->toString(),
            'paternal_surname' => $data['paternal_surname'] ?? $user->paternalSurname?->toString(),
            'maternal_surname' => $data['maternal_surname'] ?? $user->maternalSurname?->toString(),
            'phone' => $data['phone'] ?? $user->phone?->toString(),
            'department' => $data['department'] ?? $user->department?->toString(),
            'position' => $data['position'] ?? $user->position?->toString(),
        ]);

        // Update role if provided
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        // Update password if provided
        if (!empty($data['password'])) {
            $user->setPassword($data['password']);
        }

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Get user by ID
     */
    public function getUser(string $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Get all users for a company
     */
    public function getUsersByCompany(string $companyId): Collection
    {
        return $this->userRepository->findByCompany($companyId);
    }

    /**
     * Get all users for a site
     */
    public function getUsersBySite(string $siteId): Collection
    {
        return $this->userRepository->findBySite($siteId);
    }

    /**
     * Get all users by role
     */
    public function getUsersByRole(string $role): Collection
    {
        return $this->userRepository->findByRole($role);
    }

    /**
     * Deactivate user
     */
    public function deactivateUser(string $userId): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        $user->deactivate();
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Activate user
     */
    public function activateUser(string $userId): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        $user->activate();
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Assign role to user
     */
    public function assignRole(string $userId, string $role): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        $user->assignRole($role);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Sync user permissions
     */
    public function syncPermissions(string $userId, array $permissions): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        $user->syncPermissions($permissions);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Verify user email
     */
    public function verifyEmail(string $userId): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ModelNotFoundException("User not found: {$userId}");
        }

        $user->verifyEmail();
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Request password reset
     */
    public function requestPasswordReset(string $email): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            $user->generatePasswordResetToken();
            $this->userRepository->save($user);
        }

        return $user;
    }

    /**
     * Reset password with token
     */
    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !$user->validatePasswordResetToken($token)) {
            return false;
        }

        $user->setPassword($newPassword);
        $user->clearPasswordResetToken();
        $this->userRepository->save($user);

        return true;
    }

    /**
     * Get user statistics
     */
    public function getUserStats(string $companyId): array
    {
        $users = $this->userRepository->findByCompany($companyId);

        $stats = [
            'total' => $users->count(),
            'by_role' => [],
            'active' => 0,
            'inactive' => 0,
        ];

        foreach ($users as $user) {
            $role = $user->role->value;
            if (!isset($stats['by_role'][$role])) {
                $stats['by_role'][$role] = 0;
            }
            $stats['by_role'][$role]++;

            if ($user->isActive) {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }
        }

        return $stats;
    }
}
