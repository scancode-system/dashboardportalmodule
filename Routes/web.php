<?php

Route::prefix('dashboardportal')->middleware('auth')->group(function() {
	// pages
	Route::get('/', 'DashboardPortalController@index')->name('dashboardportal.index');
	Route::get('/import', 'DashboardPortalController@import')->name('dashboardportal.import');
	Route::get('/export', 'DashboardPortalController@export')->name('dashboardportal.export');

	Route::get('/update', 'DashboardPortalController@update')->name('dashboardportal.update');
	Route::get('/check', 'DashboardPortalController@check')->name('dashboardportal.check');
	Route::get('/report/failures/{file_name}', 'DashboardPortalController@reportFailures')->name('dashboardportal.report.failures');

	Route::post('/token', 'DashboardPortalController@token')->name('dashboardportal.token');


	// export
	Route::get('export/check', 'ExportController@check')->name('dashboardportal.export.check');
	Route::post('export/start', 'ExportController@start')->name('dashboardportal.export.start');
	Route::get('export/progress', 'ExportController@progress')->name('dashboardportal.export.progress');

});
