<?php
namespace Illuminate\Message;
use Illuminate\Support\ServiceProvider;
class MessageServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton('message', function()
        {
            return new Message;
        });
    }

}