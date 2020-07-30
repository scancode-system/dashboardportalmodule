<?php

namespace Modules\DashboardPortal\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingPortal extends Model
{

	protected $table = 'setting_portal';
    protected $fillable = ['last_import', 'last_export'];
    protected $dates = ['last_import', 'last_export'];

}
