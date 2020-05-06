<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    private $permissions;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->createPermissions();
        $this->createRoles();
        $this->sync();
    }

    private function createPermissions()
    {
        $permissions = [];

        // products
        $permissions = array_merge($permissions, [
            'product.index',
            'product.store',
            'product.show',
            'product.update',
            'product.updateStatus',
            'product.destroy',
            'product.approveOrDisapprove',
            'product.disapprove',
            'product.sendToReview',
        ]);

        // users
        $permissions = array_merge($permissions, [
            'user.index',
            'user.store',
            'user.show',
            'user.update',
            'user.destroy',
        ]);

        // status
        $permissions = array_merge($permissions, [
            'status.index',
        ]);

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $this->command->info(count($permissions) . ' permissões foram criadas.');
    }

    private function createRoles()
    {
        $roles = ['admin', 'defaultUser'];

        foreach ($roles as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
        $this->command->info(count($roles) . ' papéis foram criados');
    }

    private function sync()
    {

        $this->syncPermissions('defaultUser', [ // permissões do usuário padrão
            'product.index',
            'product.store',
            'product.show',
            'product.update',
            'product.updateStatus',
            'product.sendToReview',
            'status.index',
        ]);

        $this->syncPermissions('admin', Permission::all()->pluck('name')->toArray()); // administrador tem todas as permissões
    }

    private function syncPermissions($role, $permissions, $inheritance = [])
    {
        $role = Role::findByName($role);
        $permissions = array_merge($permissions, $this->getInheritedPermissions($inheritance));
        $role->syncPermissions($permissions);
        dump($role->name, $permissions);
    }

    private function getInheritedPermissions($roles)
    {
        $permissions = [];
        foreach ($roles as $role) {
            $role = Role::findByName($role);
            foreach ($role->permissions()->get() as $permission) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
}
