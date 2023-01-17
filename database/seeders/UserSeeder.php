<?php

namespace Database\Seeders;

use App\Lib\DataManager;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {



    use DataManager;

    const CED_MANAGER = 'ced manager';
    const ADMIN = 'admin';
    const MODERATOR_MANAGER = "moderator manager";
    const AGENCY = "agency";
    const COMMERCIAL = 'commercial';
    const CED = 'ced';
    const PROMOTER = 'promoter';
    const PARTICULAR = 'particular';
    const MODARATOR = 'moderator';

    public function run () {

         $this->userCanSeed();

        $admin = User::create([

                "id"=>1324983948,
                "firstname"=>"abdellatif",
                "lastname"=>"tijani",
                "username"=>"multilistadmin",
                "email"=>"tijani.idrissi.abdellatif@gmail.com",
                "phone"=>"0677734199",
                "usertype"=>1,
                "password"=>Hash::make('23061988@@tijani'),
                "authtype"=>1,
                "status"=>"10"

        ]);

        $cedManager = User::create([

                    "id"=>13249839484,
                    "firstname"=>"s",
                    "lastname"=>"tijani",
                    "username"=>"cedmanager",
                    "email"=>"s.haddi@multilist.ma",
                    "phone"=>"0677734199",
                    "usertype"=>1,
                    "password"=>Hash::make('23061988@@tijani'),
                    "authtype"=>1,
                    "status"=>"10"

            ]);


         $admin->assignRole(self::ADMIN);
        $user = User::find(88);
        $user->assignRole(self::ADMIN);
        $user->usertype = 1;
        $user->save();


        // $user = User::find(1324983952);
        // $user->authtype = 1;
        // $user->save();



        $adminPermissions = [
            'Show-users',
            'Add-user',
            'Update-user',
            'Delete-user',
            'editContract-user',
            'Changestatus-user',
            'listcoin-user',
            'changePassword-user',
            'Show-ads',
            'Add-ads',
            'SeeStates-ads',
            'Boost-ads',
            'Update-ads',
            'Delete-ads',
            'Changestatus-ads',
            'Adddsipo-ads',
            'Show-emails',
            'Show-seo',
            'Show-cycle',
            'Show-links',
            'Show-banners',
            'Show-categories',
            'Show-property-types',
            'Show-standings',
            'Show-localisations',
            'Show-plans',
            'Show-options',
            'Show-emails-catalogue',
            'Show-pages',
            'Show-privileges',
            'Show-logs',
            'Show-clients-dashboard'
        ];;
        $moderatorPermissions = [
            'Show-users',
            'Add-user',
            'Update-user',
            'Delete-user',
            'editContract-user',
            'Changestatus-user',
            'changePassword-user',
            'Show-ads',
            'Add-ads',
            'Update-ads',
            'Changestatus-ads',
            'Adddsipo-ads',
            'Show-emails',
            "Show-states-dashboard"
        ];
        $moderatorManagerPermissions = [
            'Show-users',
            'Add-user',
            'Update-user',
            'Delete-user',
            'editContract-user',
            'Changestatus-user',
            'changePassword-user',
            'Show-ads',
            'Add-ads',
            'Update-ads',
            'Changestatus-ads',
            'Adddsipo-ads',
            'Show-emails',
            'Show-logs',
            "Show-states-dashboard"
        ];
        $cedManagerPermissions = [
            'Show-users',
            'Add-user',
            'Update-user',
            'editContract-user',
            'listcoin-user',
            'Show-ads',
            'Update-ads',
            'Changestatus-ads',
            'SeeStates-ads',
            'Boost-ads',
            'Show-logs'
        ];
        $cedPermissions = [
            'Show-users',
            'Add-user',
            'Update-user',
            'editContract-user',
            'listcoin-user',
            'Show-ads',
            'Update-ads',
            'Changestatus-ads',
            'SeeStates-ads',
            'Boost-ads',
        ];
        Role::find(1)->syncPermissions($adminPermissions); //admin
        Role::find(2)->syncPermissions($moderatorManagerPermissions); //moderator manager
        Role::find(3)->syncPermissions($moderatorPermissions); //moderator
        Role::find(4)->syncPermissions([]); //comercial
        Role::find(5)->syncPermissions($cedManagerPermissions); //ced manager
        Role::find(6)->syncPermissions($cedPermissions); //ced

    }
}



?>
