<?php

namespace Modules\DashboardPortal\Services\Export;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Repositories\OrderRepository;
use Modules\Dashboard\Entities\Report;
use Modules\Dashboard\Entities\Txt;
use Modules\DashboardPortal\Repositories\SettingPortalRepository;
use ZipArchive;



class PortalUpdateService {

    const PATH = 'export/';


    public function start($token){
        self::status(true);
        $this->loadPdfs();
        $this->loadXlsx();
        $this->loadTxts();
        $this->zip();
        $this->send($token);
        self::message('Portal atualizado.');
        self::status(false);
    }

    public function loadPdfs(){
        self::message('Carregando PDFs.');
        $orders = OrderRepository::loadClosedOrders();
        foreach ($orders as $order) {
            if (Storage::exists(self::uriNewPdf($order))) {
                Storage::delete(self::uriNewPdf($order));
            }
            Storage::copy(self::uriOldPdf($order), self::uriNewPdf($order));
        }
    }

    public function loadXlsx(){
        self::message('Carregando XLSXs.');
        $reports = Report::all();
        foreach ($reports as $report) {
            Excel::store(new $report->export_class, self::PATH.'xlsx/'.$report->file_alias);
        }
    }

    public function loadTxts(){
        self::message('Carregando TXTs.');
        $txts = Txt::all();
        foreach ($txts as $txt) {
            $txt_service = new $txt->service_class($txt->alias, self::PATH.'txt');
            $txt_service->run();
        }
    }


    public function zip()
    {
        self::message('Compactando arquivos');
        $this->zipFolder(SELF::PATH.'pdf');
        $this->zipFolder(SELF::PATH.'xlsx');
        $this->zipFolder(SELF::PATH.'txt');
        $this->zipFolder('export');
    }

    public function zipFolder($path)
    {
        self::message('Compactando arquivos');
        $files = Storage::allFiles($path);
        $zip_path = storage_path('app/'.$path.'.zip');
        $zip = new ZipArchive;
        $zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $file) {
            $file_name = str_replace($path, '', $file);
            $zip->addFile(storage_path('app/'.$file), $file_name);
        }
        $zip->close();

        Storage::deleteDirectory($path);
    }

    public function send($token){
        self::message('Enviando arquivos para o portal.');

        $token_exploded = explode(':', base64_decode($token));
        $login = $token_exploded[0];
        $password = $token_exploded[1];
        $event = $token_exploded[2];


        $response = Http::withBasicAuth($login, $password)->attach('attachment', file_get_contents(storage_path('app/export.zip')), 'arquivo'
    )->post(config('dashboardportal.url'). '/api/pos/'.$event);
        Storage::delete('export.zip');
        //dd(json_decode($response->body())->sync);
        SettingPortalRepository::update(['last_export' => json_decode($response->body())->sync]);

    }

    private static function uriOldPdf($order){
        return 'public/pedidos/pedido_'.$order->id.'.pdf';
    }

    private static function uriNewPdf($order){
        return self::PATH.'pdf/pedido_'.$order->id.'.pdf';
    }


    public static function status($status = null){
        if(is_null($status)){
            return session('dashboardportal.export.status', false);
        } else {
            session(['dashboardportal.export.status' => $status]);
            session()->save();
        }
    }

    public static function message($message = null){
        if(is_null($message)){
            return session('dashboardportal.export.message', '');
        } else {
            session(['dashboardportal.export.message' => $message]);
            session()->save();
        }
    }


/*
	const CHECK = 'check';
	const EVENT = 'all';
	const DOWNLOAD = 'download';

	private $login;
	private $password;
	private $event;

	public function __construct($login, $password, $event){
		$this->login = $login;
		$this->password = $password;
		$this->event = $event;
	}

	public function check() {
		$result_call = $this->call(self::CHECK);
		if($result_call->status == 202)
		{
			return true;
		} else {
			return false;
		} 
	}

	public function event() {
		return $this->call(self::EVENT);
	}

	public function download() {
		$result_call = $this->call(self::DOWNLOAD);
		if($result_call->status == 200)
		{
			return $result_call->data;
		} else {
			return false;
		} 
	}

	public function call($method){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, config('dashboardportal.url'). '/api/'.$method.'/'.$this->event);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($curl, CURLOPT_USERPWD, $this->login.':'.$this->password);
		$data = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		return (object)['data' => $data, 'status' => $status];
	}
    */

}

