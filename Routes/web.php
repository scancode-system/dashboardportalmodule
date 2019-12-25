<?php

Route::prefix('dashboardportal')->middleware('auth')->group(function() {
    Route::get('/', 'DashboardPortalController@index')->name('dashboardportal.index');

    Route::post('/token', 'DashboardPortalController@token')->name('dashboardportal.token');
    Route::post('/update', 'DashboardPortalController@update')->name('dashboardportal.update');
});
