<?php

namespace Modules\DashboardPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\DashboardPortal\Http\ViewComposers\UpdateComposer;

class ViewComposerServiceProvider extends ServiceProvider {

	public function boot() {
		View::composer('dashboardportal::update', UpdateComposer::class);
	}

	public function register() {
        //
	}

}
