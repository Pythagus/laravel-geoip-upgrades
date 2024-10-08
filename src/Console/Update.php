<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP\Console;

use Illuminate\Console\Command;
use InteractionDesignFoundation\GeoIP\Exceptions\MissingConfigurationException;

class Update extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'geoip:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update GeoIP database files to the latest version';

    /**
     * Execute the console command for Laravel 5.5 and newer.
     *
     * @return void
     */
    public function handle()
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     * @deprecated Use {@see self::handle()} instead.
     *
     * @return void
     */
    public function fire()
    {
        // Get default service
        try {
            $service = app('geoip')->getService();
        } catch(MissingConfigurationException $e) {
            $this->components->error($e->getMessage()) ;
            
            return static::FAILURE ;
        }

        // Ensure the selected service supports updating
        if (method_exists($service, 'update') === false) {
            $this->info('The current service "' . get_class($service) . '" does not support updating.');

            return;
        }

        $this->comment('Updating...');

        // Perform update
        if ($result = $service->update()) {
            $this->info($result);
        } else {
            $this->error('Update failed!');
        }
    }
}
