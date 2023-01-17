<?php
namespace Database\Seeders;use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {         // create permissions
        $permessions = [
            ['name' => 'Show-users'],
            ['name' => 'Add-user'],
            ['name' => 'Update-user'],
            ['name' => 'Delete-user'],
            ['name' => 'editContract-user'],
            ['name' => 'Changestatus-user'],
            ['name' => 'listcoin-user'],
            ['name' => 'changePassword-user'],
            ['name' => 'Show-ads'],
            ['name' => 'Add-ads'],
            ['name' => 'SeeStates-ads'],
            ['name' => 'Boost-ads'],
            ['name' => 'Update-ads'],
            ['name' => 'Delete-ads'],
            ['name' => 'Changestatus-ads'],
            ['name' => 'Adddsipo-ads'],
            ['name' => 'Show-emails'],
            ['name' => 'Show-seo'],
            ['name' => 'Show-cycle'],
            ['name' => 'Show-links'],
            ['name' => 'Show-banners'],
            ['name' => 'Show-categories'],
            ['name' => 'Show-property-types'],
            ['name' => 'Show-standings'],
            ['name' => 'Show-localisations'],
            ['name' => 'Show-plans'],
            ['name' => 'Show-options'],
            ['name' => 'Show-emails-catalogue'],
            ['name' => 'Show-pages'],
            ['name' => 'Show-privileges'],
            ['name' => 'Show-logs'],
            ['name' => 'Show-states-dashboard'],
            ['name' => 'Show-clients-dashboard'],
            ['name' => 'Show-notifications'],
        ];

        foreach ($permessions as $value) {
            Permission::create($value);
        }

        $roles = [
            ['id' => 1,'name' => 'admin'],
            ['id' => 2,'name' => 'moderator manager'],
            ['id' => 3,'name' => 'moderator'],
            ['id' => 4,'name' => 'comercial'],
            ['id' => 5,'name' => 'ced manager'],
            ['id' => 6,'name' => 'ced'],
        ];

        foreach ($roles as $value) {
            Role::create($value);
        }

    }
}
