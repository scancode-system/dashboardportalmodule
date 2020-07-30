<?php

namespace Modules\DashboardPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\DashboardPortal\Services\Export\PortalUpdateService;

class ExportController extends Controller
{

    public function check(Request $request, PortalUpdateService $portal_update_service){
        PortalUpdateService::status(false);
        return ['status' => PortalUpdateService::status()];
    }

    public function start(Request $request, PortalUpdateService $portal_update_service){
        $portal_update_service->start($request->token);
    }    

    public function progress(Request $request){
        return view('dashboardportal::export.progress');
    }

}
