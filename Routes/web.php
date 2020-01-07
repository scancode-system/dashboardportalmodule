<?php

Route::prefix('dashboardportal')->middleware('auth')->group(function() {
	Route::get('/', 'DashboardPortalController@index')->name('dashboardportal.index');

	Route::get('/update', 'DashboardPortalController@update')->name('dashboardportal.update');
	Route::get('/check', 'DashboardPortalController@check')->name('dashboardportal.check');
	Route::get('/report/failures/{file_name}', 'DashboardPortalController@reportFailures')->name('dashboardportal.report.failures');

	Route::post('/token', 'DashboardPortalController@token')->name('dashboardportal.token');
});
