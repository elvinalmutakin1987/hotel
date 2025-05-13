<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'guests',
            'reservations',
            'checkin',
            'checkout',
            'cleaning',
            'laundry',
            'roomtypes',
            'rooms',
            'additionalitems',
            'contracts',
            'billings',
            'spaceforrent',
            'costlist',
            'supplier',
            'purchase',
            'goodreceipt',
            'dispatching',
            'stock',
            'stockopname',
            'stockout',
            'transactions',
            'invoices',
            'expenses',
            'chartofaccounts',
            'finansialreports',
            'journalentries',
            'balancesheet',
            'cashflow',
            'profitlost',
            'staff',
            'user',
            'role'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $lobbyRole = Role::firstOrCreate(['name' => 'lobby']);
        $inventoryRole = Role::firstOrCreate(['name' => 'inventory']);
        $accountingRole = Role::firstOrCreate(['name' => 'accounting']);
        $bpdRole = Role::firstOrCreate(['name' => 'bpd']);
        $housekeepingRole = Role::firstOrCreate(['name' => 'housekeeping']);

        $adminRole->syncPermissions($permissions);

        $managerRole->syncPermissions($permissions);

        $lobbyRole->syncPermissions([
            'guests',
            'reservations',
            'checkin',
            'checkout',
        ]);

        $inventoryRole->syncPermissions([
            'supplier',
            'purchase',
            'stock',
            'goodreceipt',
            'dispatching',
            'stockopname',
            'stockout'
        ]);

        $bpdRole->syncPermissions([
            'profitlost',
        ]);

        $accountingRole->syncPermissions([
            'transactions',
            'invoices',
            'expenses',
            'chartofaccounts',
            'finansialreports',
            'journalentries',
            'balancesheet',
            'cashflow',
            'profitlost',
        ]);

        $housekeepingRole->syncPermissions([
            'laundry',
            'cleaning',
        ]);
    }
}
