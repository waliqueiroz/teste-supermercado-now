<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getCurrentUser($user)
    {
        $permissions = $user->getAllPermissions()->pluck('name');
        $roles = $user->getRoleNames()->all();
        $currentUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'roles' => $roles,
            'permissions' => $permissions,
        ];
        return $currentUser;
    }

    public function getByEmail($email)
    {
        $user = User::where('email', '=', $email)->first();
        return $user;
    }

    public function index($filters)
    {
        $response = User::with('roles')->where(function ($query) use ($filters) {
            if (!empty($filters["name"])) {
                $query->where("name", 'ilike', "%" . $filters["name"] . "%");
            }
            if (!empty($filters["email"])) {
                $query->where("email", 'ilike', "%" . $filters["email"] . "%");
            }
            if (!empty($filters["role"])) {
                $query->whereHas("roles", function ($q) use ($filters) {
                    $q->where("name", $filters["role"]);
                });
            }
        })->orderBy('created_at', 'desc');

        if (!empty($filters["paginate"])) {
            return $response->paginate(20);
        }

        return $response->get();
    }


    public function store($userData)
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
        ]);
        $user->assignRole($userData['role']);
        return $user;
    }

    public function update($id, $userData)
    {
        $user = User::find($id);
        if (!empty($userData['password'])) { // se o campo de senha não vier vier vazio
            $userData['password'] = bcrypt($userData['password']); //criptografa
        } else {
            unset($userData['password']); // caso contrario so arranca do array
        }
        $user->update($userData);
        $user->syncROles($userData['role']);
        return $user;
    }

    public function show($id)
    {
        $user = User::find($id);
        $user->role = $user->roles[0]->name;
        return $user;
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $response = $user->delete();
        return $response;
    }
}
