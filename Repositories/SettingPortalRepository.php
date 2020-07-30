<?php

namespace Modules\DashboardPortal\Repositories;

use Modules\DashboardPortal\Entities\SettingPortal;


class SettingPortalRepository
{
	// CREATES
	public static function init(){
		return SettingPortal::create([]);
	}

	// LOADS
	public static function load(){
		return SettingPortal::first();
	}

	// UPDATES
	public static function update($data){
		$setting_portal = SettingPortal::first();
		$setting_portal->update($data);
		return $setting_portal;
	}


	// DESTOY
	public static function end(){
		return SettingPortal::truncate();
	}

}
