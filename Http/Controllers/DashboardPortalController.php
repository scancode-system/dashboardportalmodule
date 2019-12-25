<?php

namespace Modules\DashboardPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\DashboardPortal\Services\TokenService;
use Modules\DashboardPortal\Services\UpdateService;

class DashboardPortalController extends Controller
{

    public function index()
    {
        return view('dashboardportal::index');
    }

    public function token(Request $request)
    {
        $token_service = new TokenService($request->token);
        $token_service->import();
    }

    public function update(Request $request)
    {
        $update_service = new UpdateService();
        $response = $update_service->update();
        return $response;
    }

}
