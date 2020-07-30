<?php

namespace Modules\DashboardPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\DashboardPortal\Http\ViewComposers\UpdateComposer;
use Modules\DashboardPortal\Http\ViewComposers\Export\ProgressComposer;

class ViewComposerServiceProvider extends ServiceProvider {

	public function boot() {
		View::composer('dashboardportal::update', UpdateComposer::class);
		View::composer('dashboardportal::export.progress', ProgressComposer::class);
	}

	public function register() {
        //
	}

}
