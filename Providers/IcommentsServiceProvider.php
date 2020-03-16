<?php

namespace Modules\Icomments\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Icomments\Events\Handlers\RegisterIcommentsSidebar;

class IcommentsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterIcommentsSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('comments', array_dot(trans('icomments::comments')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('icomments', 'permissions');
        $this->publishConfig('icomments', 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Icomments\Repositories\CommentRepository',
            function () {
                $repository = new \Modules\Icomments\Repositories\Eloquent\EloquentCommentRepository(new \Modules\Icomments\Entities\Comment());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Icomments\Repositories\Cache\CacheCommentDecorator($repository);
            }
        );
// add bindings

    }
}
