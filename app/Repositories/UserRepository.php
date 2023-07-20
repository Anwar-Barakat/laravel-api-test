<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        $created = User::query()->create([
            'name'      => data_get($attributes, 'name', 'Username'),
            'email'     => data_get($attributes, 'email'),
            'password'  => Hash::make(data_get($attributes, 'password'))
        ]);

        return $created;
    }

    public function update($user, array $attributes)
    {
        $updated = $user->update(['name' => data_get($attributes, 'name')]);
        if (!$updated)
            throw new \Exception('Failed to update');

        return $updated;
    }

    public function delete($user)
    {
        $deleted = $user->forceDelete();
        if (!$deleted)
            throw new \Exception('Could not delete user');

        return $deleted;
    }
}
