<?php

namespace Thotam\ThotamTeam\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Thotam\ThotamTeam\Models\PhanLoaiNhom;

class Team_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //PhanLoaiNhom Seed
        PhanLoaiNhom::updateOrCreate(
            ['id' => 1],
            [
                'name' => "Sản xuất",
                'tag' => "SX",
                'order' => 1,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 2],
            [
                'name' => "Văn phòng",
                'tag' => "VP",
                'order' => 2,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 3],
            [
                'name' => "Kinh doanh",
                'tag' => "KD",
                'order' => 3,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 4],
            [
                'name' => "Marketing",
                'tag' => "MKT",
                'order' => 4,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 5],
            [
                'name' => "Thực tập sinh",
                'tag' => "TTS",
                'order' => 5,
                'active' => true
            ]
        );

        //Role and Permission
        $permission[] = Permission::updateOrCreate([
            'name' => 'view-team'
        ],[
            "description" => "Xem Nhóm",
            "group" => "Team",
            "order" => 1,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'add-team'
        ],[
            "description" => "Thêm Nhóm",
            "group" => "Team",
            "order" => 2,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'edit-team'
        ],[
            "description" => "Sửa Nhóm",
            "group" => "Team",
            "order" => 3,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'delete-team'
        ],[
            "description" => "Xóa Nhóm",
            "group" => "Team",
            "order" => 4,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'set-member-team'
        ],[
            "description" => "Set nhân viên cho Nhóm",
            "group" => "Team",
            "order" => 5,
            "lock" => true,
        ]);

        $super_admin = Role::updateOrCreate([
            'name' => 'super-admin'
        ],[
            "description" => "Super Admin",
            "group" => "Admin",
            "order" => 1,
            "lock" => true,
        ]);

        $admin = Role::updateOrCreate([
            'name' => 'admin'
        ],[
            "description" => "Admin",
            "group" => "Admin",
            "order" => 2,
            "lock" => true,
        ]);

        $admin_team = Role::updateOrCreate([
            'name' => 'admin-team'
        ],[
            "description" => "Admin Team",
            "group" => "Admin",
            "order" => 4,
            "lock" => true,
        ]);

        $super_admin->givePermissionTo($permission);
        $admin->givePermissionTo($permission);
        $admin_team->givePermissionTo($permission);

    }
}
