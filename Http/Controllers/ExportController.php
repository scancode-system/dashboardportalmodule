<?php

namespace Modules\DashboardPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\DashboardPortal\Services\Export\PortalUpdateService;
use Illuminate\Support\Facades\Storage;

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

    public function auto(Request $request){
        if($request->action == 'save'){
            Storage::put('exportauto/time', $request->auto);
            return back()->with('success', 'Horário automatizado salvo.');
        } else {
            Storage::delete('exportauto/time');
            return back()->with('success', 'Atualização automatica desativada.');
        }
    }

}
