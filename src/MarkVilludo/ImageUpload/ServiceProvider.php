<?php 

namespace MarkVilludo\ImageUpload;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MarkVilludo\ImageUpload\ImageUpload;

class ServiceProvider extends BaseServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {      
        
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../../config/imageUpload.php', 'imageUpload'
        // );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('imageUpload', function() {
        //     return new ImageUpload();
        // });
    }

}
