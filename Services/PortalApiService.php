<?php

namespace Modules\DashboardPortal\Services;

class PortalApiService {

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

}

/*

    private $token;
    private $status;
    private $event;
    private $files;
    private $checkFiles = ['login' => false, 'logo' => false, 'transporter' => false, 'clients' => false, 'dealers' => false, 'products' => false, 'payments' => false, 'product_compounds' => false];

    public function __construct($token) {
        $this->token = $token;
    }

    private function storeDisk($msg) {
        Storage::disk('local')->put('/import/token', $msg, 'public');
    }

    public function connect() {
        $this->request_info();
        $this->request_download();
    }

    private function request_info(){


        $token = explode(':', base64_decode($this->token));

        $this->storeDisk('Buscando informações da empresa');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('IMPORT_TOKEN') . '/api/token/'.$token[2]);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_USERPWD, $token[0].':'.$token[1]);
        $this->event = json_decode(curl_exec($curl));

        //dd(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        curl_close($curl);
    } 

    private function request_download(){
        $token = explode(':', base64_decode($this->token));

        $this->storeDisk('Fazendo Download dos arquivos');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('IMPORT_TOKEN'). '/api/token/'.$token[2].'/files');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_USERPWD, $token[0].':'.$token[1]);
        $this->data = curl_exec($curl);
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    } 

    public function getStatus() {
        return $this->status;
    }

    public function run() {
        $this->store();
        $this->extractZip();
        $this->loadFiles();
        $this->storeDisk('Importação concluida com sucesso');
    }

    public function store() {
        $this->storeDisk('Registando empresa');

        //dd($this->event);
        //dd($this->event);
        $data = [
            'stand' => $this->event->company->name,
            'pdf_title' => $this->event->name,
            'emp_cnpj' => $this->event->company->company_info->cnpj,
            'emp_razao_social' => $this->event->company->company_info->company_name,
            'emp_nome_fantasia' => $this->event->company->company_info->trade_name,
            'emp_email' => $this->event->company->email,
            'emp_telefone' => $this->event->company->company_info->phone,
            'emp_cep' => $this->event->company->company_address->zip_code,
            'emp_uf' => $this->event->company->company_address->st,
            'emp_cidade' => $this->event->company->company_address->city,
            'emp_bairro' => $this->event->company->company_address->neighborhood,
            'emp_logradouro' => $this->event->company->company_address->address,
            'num_inicial_pedido' => $this->event->system_setting->start_id_order,
            'qtd_vias_impressao' => $this->event->system_setting->number_sheets,
            'obs_pedidos' => $this->event->system_setting->note,
            'email_from' => $this->event->system_setting->email_from,
            'email_subject' => $this->event->system_setting->email_subject,
            'email_note' => $this->event->system_setting->email_note
        ];

        Config::all()->first()->update($data);
    }

    private function extractZip() {
        if($this->status == 200){
            $this->storeDisk('Verificando arquivos');

            $files = glob(storage_path('app/token/*'));
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file);
            }

            Storage::disk('local')->put('token/files.zip', $this->data, 'public');

            $zipperClass = new Zipper();
            $zipperClass->make(storage_path('app/token/') . 'files.zip')->extractTo(storage_path('app/token'));
            $zipperClass->close();

            unlink(storage_path('app/token/files.zip'));

            if (is_file(storage_path('app/token/images/logo/logo.png'))) {
                $this->checkFiles['logo'] = true;
            }
            if (is_file(storage_path('app/token/images/logo/logo.png'))) {
                $this->checkFiles['logo'] = true;
            }
            if (is_file(storage_path('app/token/login.png'))) {
                $this->checkFiles['login'] = true;
            }
            if (is_file(storage_path('app/token/clientes.xlsx'))) {
                $this->checkFiles['clients'] = true;
            }
            if (is_file(storage_path('app/token/representantes.xlsx'))) {
                $this->checkFiles['dealers'] = true;
            }
            if (is_file(storage_path('app/token/produtos.xlsx'))) {
                $this->checkFiles['products'] = true;
            }
            if (is_file(storage_path('app/token/pagamentos.xlsx'))) {
                $this->checkFiles['payments'] = true;
            }
            if (is_file(storage_path('app/token/transportadora.xlsx'))) {
                $this->checkFiles['transporter'] = true;
            }
            if (is_file(storage_path('app/token/produto_composto.xlsx'))) {
                $this->checkFiles['product_compounds'] = true;
            }
        }
    }

    private function loadFiles() {
//        dd($this->checkFiles);
        foreach ($this->checkFiles as $file => $status) {
            if ($status) {
                switch ($file) {
                    case 'logo':
                    $this->loadLogo();
                    break;
                    case 'login':
                    $this->loadLogin();
                    break;
                    case 'clients':
                    $this->loadClients();
                    break;
                    case 'dealers':
                    $this->loadDealers();
                    break;
                    case 'products':
                    $this->loadProducts();
                    break;
                    case 'payments':
                    $this->loadPayments();
                    break;
                    case 'transporter':
                    $this->loadTransporter();
                    break;
                    case 'product_compounds':
                    $this->loadProductCompounds();
                    break;
                }
            }
        }
        $this->copyImages();
    }
    
    private function copyImages() {
        $files = Storage::files('token/images/produtos');
        foreach ($files as $file) {
            $file_name = str_replace('token/images/produtos/', '', $file);
            if(Storage::exists('public/image/produtos/'.$file_name)){
                Storage::delete('public/image/produtos/'.$file_name);
            }
            Storage::copy($file, 'public/image/produtos/'.$file_name);
        }
    }

    private function loadLogo() {
        $this->storeDisk('Salvando Logo');
        Storage::disk('public')->put('image/emp_logo.png', file_get_contents(storage_path('app/token/images/logo/logo.png')), 'public');
    }

    private function loadLogin() {
        $this->storeDisk('Salvando Login');
        Storage::disk('public')->put('image/login_image.png', file_get_contents(storage_path('app/token/login.png')), 'public');
    }

    private function loadClients() {
        $this->storeDisk('Cadastrando Clientes');
        $import = new ImportClients(storage_path('app/token/clientes.xlsx'), 'clients_token');
        $import->load();
    }

    private function loadDealers() {
        $this->storeDisk('Cadastrando Representantes');
        $import = new ImportDealers(storage_path('app/token/representantes.xlsx'), 'dealers_token');
        $import->load();
    }

    private function loadProducts() {
        $this->storeDisk('Cadastrando produtos');
        $import = new ImportProducts(storage_path('app/token/produtos.xlsx'), 'products_token');
        $import->load();
    }

    private function loadPayments() {
        $this->storeDisk('Cadastrando pagamentos');
        $import = new ImportPayments(storage_path('app/token/pagamentos.xlsx'), 'payments_token');
        $import->load();
    }   

    private function loadTransporter() {
        $this->storeDisk('Cadastrando transportadoras');
        $import = new ImportTransportadora(storage_path('app/token/transportadora.xlsx'), 'transporter_token');
        $import->load();
    }


    private function loadProductCompounds() {
        $this->storeDisk('Cadastrando produtos compostos');
        $import = new ImportProdutosCompostos(storage_path('app/token/produto_composto.xlsx'), 'product_compounds_token');
        $import->load();
    } 
    
    
    public static function getInfo() {
        $data['info'] =  Storage::disk('local')->get('/import/token');
        $data['clients'] = ImportClients::getInfo('clients_token');
        $data['dealers'] = ImportDealers::getInfo('dealers_token');
        $data['products'] = ImportProducts::getInfo('products_token');
        $data['payments'] = ImportPayments::getInfo('payments_token');
        $data['transporter'] = ImportProducts::getInfo('transporter_token');
        $data['product_compounds'] = ImportPayments::getInfo('product_compounds_token');        
        return $data;
    }    
    

}
*/