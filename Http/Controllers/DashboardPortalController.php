<?php

namespace Modules\DashboardPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\DashboardPortal\Services\TokenService;
use Modules\DashboardPortal\Services\SessionService;
use Modules\DashboardPortal\Repositories\SettingPortalRepository;


class DashboardPortalController extends Controller
{

    public function index()
    {
        $token = null;
        if(Storage::exists('token/token')){
            $token = Storage::get('token/token');
        }
        return view('dashboardportal::index', ['setting_portal' => SettingPortalRepository::load(), 'token' => $token]);
    }

    public function save(Request $request){
        if($request->action == 'save'){
            Storage::put('token/token', $request->token);
            return back()->with('success', 'Token salvo.');
        } else {
            Storage::delete('token/token');
            return back()->with('success', 'Token removido.');
        }
    }

    public function import()
    {
        $token = null;
        if(Storage::exists('token/token')){
            $token = Storage::get('token/token');
        }
        return view('dashboardportal::import', ['token' => $token]);
    }

    public function export()
    {
        $token = null;
        if(Storage::exists('token/token')){
            $token = Storage::get('token/token');
        }
        $auto = null;
        if(Storage::exists('exportauto/time')){
            $auto = Storage::get('exportauto/time');
        }
        return view('dashboardportal::export', ['token' => $token, 'auto' => $auto]);
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
