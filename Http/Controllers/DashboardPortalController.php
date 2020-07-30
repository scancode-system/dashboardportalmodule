<?php

namespace Modules\DashboardPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\DashboardPortal\Services\TokenService;
use Modules\DashboardPortal\Services\SessionService;
use Modules\DashboardPortal\Repositories\SettingPortalRepository;


class DashboardPortalController extends Controller
{

    public function index()
    {
        return view('dashboardportal::index', ['setting_portal' => SettingPortalRepository::load()]);
    }

    public function import()
    {
        return view('dashboardportal::import');
    }

    public function export()
    {
        return view('dashboardportal::export');
    }


    public function token(Request $request)
    {
        $token_service = new TokenService($request->token);
        $token_service->import();
    }

    public function update(Request $request)
    {
        return view('dashboardportal::update');
    }

    public function check(Request $request)
    {
        return response()->json(['importing' => SessionService::importing()]);
    }

    public function reportFailures(Request $request, $file_name)
    {
        return response()->download(storage_path('app/failures/'.$file_name));
    }

}
