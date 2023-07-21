<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
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
        throw_if(!$created, GeneralJsonException::class, 'Failed to create');
        return $created;
    }

    public function update($user, array $attributes)
    {
        $updated = $user->update(['name' => data_get($attributes, 'name')]);
        throw_if(!$updated, GeneralJsonException::class, 'Failed to update');
        return $updated;
    }

    public function delete($user)
    {
        $deleted = $user->forceDelete();
        throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete');
        return $deleted;
    }
}