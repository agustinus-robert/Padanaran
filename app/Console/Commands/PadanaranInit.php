<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\StreamOutput;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TenantSeeder;

class PadanaranInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'padanaran:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing Applications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('key:generate');

        if (env('TENANT_PACK') == true) {
            $dbName = "central_tenant";

            // Gunakan koneksi admin (default pgsql), bukan tenant
            $adminConnection = DB::connection('pgsql');

            $exists = $adminConnection->selectOne(
                "SELECT 1 FROM pg_database WHERE datname = ?",
                [$dbName]
            );

            if ($exists) {
                // Putus semua koneksi aktif ke database tenant
                $adminConnection->statement("REVOKE CONNECT ON DATABASE \"$dbName\" FROM PUBLIC");
                $adminConnection->statement("
                    SELECT pg_terminate_backend(pid)
                    FROM pg_stat_activity
                    WHERE datname = '$dbName'
                    AND pid <> pg_backend_pid()
                ");

                // Drop DB
                $adminConnection->statement("DROP DATABASE \"$dbName\"");
                $this->info("Database {$dbName} dropped.");
            }

            // Buat DB tenant
            $adminConnection->statement("CREATE DATABASE \"$dbName\" WITH OWNER = '" . env('DB_USERNAME') . "'");
            $this->info("Database {$dbName} created.");

            // Tambahkan koneksi tenant dinamis
            config([
                'database.connections.central_tenant' => [
                    'driver'   => 'pgsql',
                    'host'     => env('DB_HOST', '127.0.0.1'),
                    'port'     => env('DB_PORT', '5432'),
                    'database' => $dbName,
                    'username' => env('DB_USERNAME', 'postgres'),
                    'password' => env('DB_PASSWORD', 'postgres'),
                ]
            ]);

            DB::purge('central_tenant');
            DB::reconnect('central_tenant');

            // Jalankan migrasi tenant
            Artisan::call('migrate', [
                '--path'     => '/database/migrations/tenant',
                '--database' => 'central_tenant',
                '--force'    => true,
            ]);
            $this->info(Artisan::output());

            // Jalankan seeder tenant
            Artisan::call('db:seed', [
                '--class'    => TenantSeeder::class,
                '--database' => 'central_tenant',
                '--force'    => true,
            ]);
            $this->info(Artisan::output());

            $this->info("Central Tenant migrated successfully.");
        }

        // MAIN DB migrate + seed
        // $this->line("Running " . config('app.name') . " main migration ...");
        // $this->info("Please wait, system will automatically setup your environment!");
        // $this->call('migrate:fresh', [], new StreamOutput(fopen("php://output", "w")));

        // $this->line("Running " . config('app.name') . " main seeders ...");
        // $this->call('db:seed', [], new StreamOutput(fopen("php://output", "w")));

        // $this->callSilently('optimize:clear');

        // $this->info("Installing Applications successfully!");
        // ================== Main DB ==================

        $dbTenant = "boardings";

        // Gunakan koneksi admin (default pgsql), bukan tenant
        $tenantConnection = DB::connection('pgsql_admin');

        $existsTenant = $tenantConnection->selectOne(
            "SELECT 1 FROM pg_database WHERE datname = ?",
            [$dbTenant]
        );

        if ($existsTenant) {
            // Putus semua koneksi aktif ke database tenant
            $tenantConnection->statement("REVOKE CONNECT ON DATABASE \"$dbTenant\" FROM PUBLIC");
            $tenantConnection->statement("
                    SELECT pg_terminate_backend(pid)
                    FROM pg_stat_activity
                    WHERE datname = '$dbTenant'
                    AND pid <> pg_backend_pid()
                ");

            // Drop DB
            $tenantConnection->statement("DROP DATABASE \"$dbTenant\"");
            $this->info("Database {$dbTenant} dropped.");
        }

        $tenantConnection->statement("CREATE DATABASE \"$dbTenant\" WITH OWNER = '" . env('DB_USERNAME') . "'");
        $this->info("Database {$dbTenant} created.");

        $mainDb = [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', '127.0.0.1'), // host utama
            'port'     => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', $dbTenant), // DB utama
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', 'postgres'),
        ];

        config(['database.connections.pgsql' => $mainDb]);
        DB::purge('pgsql');
        DB::reconnect('pgsql');

        $this->line("Running " . config('app.name') . " main migration ...");
        $this->info("Please wait, system will automatically setup your environment!");

        Artisan::call('migrate:fresh', [
            '--database' => 'pgsql',
            '--force'    => true,
        ], new StreamOutput(fopen("php://output", "w")));

        $this->line("Running " . config('app.name') . " main seeders ...");

        Artisan::call('db:seed', [
            '--database' => 'pgsql',
            '--force'    => true,
        ], new StreamOutput(fopen("php://output", "w")));

        $this->callSilently('optimize:clear');

        $this->info("Installing Applications successfully!");

    }



    /**
     * Write a new environment file with the given key value.
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    public function setEnvironmentValue($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
