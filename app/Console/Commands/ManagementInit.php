<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\ClientRepository;
use Symfony\Component\Console\Output\StreamOutput;

class ManagementInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'management:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing module Management';

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
    public function handle(ClientRepository $clients)
    {
        $this->call('key:generate');

        $this->line("Running " . config('app_name') . " main migration ...");
        $this->info("Please wait, system will automatically setup your environment!");
        $this->call('migrate:fresh', [], new StreamOutput(fopen("php://output", "w")));

        $this->line("Creating passport personal client ...");
        $personal = $clients->createPersonalAccessClient(null, config('app.name') . ' Personal Access Client', 'http://localhost');
        $this->setEnvironmentValue('OAUTH_PERSONAL_CLIENT_ID', $personal->id);
        $this->setEnvironmentValue('OAUTH_PERSONAL_CLIENT_SECRET', $personal->plainSecret);
        $this->info("Successfully created passport personal client");

        $this->line("Creating passport password client ...");
        $password = $clients->createPasswordGrantClient(null, config('app.name') . ' Password Grant Client', 'http://localhost', (in_array('users', array_keys(config('auth.providers'))) ? 'users' : null));
        $this->setEnvironmentValue('OAUTH_PASSWORD_CLIENT_ID', $password->id);
        $this->setEnvironmentValue('OAUTH_PASSWORD_CLIENT_SECRET', $password->plainSecret);
        $this->info("Successfully created passport password client");

        $this->line("Running " . config('app_name') . " main seeders ...");
        $this->call('db:seed', [], new StreamOutput(fopen("php://output", "w")));

        $this->callSilently('optimize:clear');

        $this->warn("All is well, goodbye!");
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
