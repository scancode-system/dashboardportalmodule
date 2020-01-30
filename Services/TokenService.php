<?php

namespace Modules\DashboardPortal\Services;

use Modules\DashboardPortal\Services\PortalApiService;
use Modules\ImportWidget\Services\SessionService;
use Modules\DashboardPortal\Services\FileService;
use Nwidart\Modules\Facades\Module;
use Exception;

class TokenService {

    private $login;
    private $password;
    private $event;

    public function __construct($token){
        $token_exploded = explode(':', base64_decode($token));
        if(count($token_exploded) == 3)
        {
            $this->login = $token_exploded[0];
            $this->password = $token_exploded[1];
            $this->event = $token_exploded[2];
        } else {
            SessionService::message('token', 'Token Inválido.');
            throw new Exception("Token inválido");
        }
    }

    public function import() {
        SessionService::start('token');

        SessionService::message('token', 'Conectando ao portal.');
        $portal_api_service = new PortalApiService($this->login, $this->password, $this->event);
        if($portal_api_service->check())
        {
            SessionService::message('token', 'Buscando dados do evento.');
            $imports = json_decode($portal_api_service->event()->data);

            SessionService::message('token', 'Fazendo Download de arquivos e imagens.');
            FileService::storage($portal_api_service->download());
            
            SessionService::message('token', 'Configurando imagens.');
            foreach ($imports->images as $import_image) 
            {
                $class_method = explode('@', $import_image->portal_service);
                $module = $class_method[0];
                $method = $class_method[1];

                $path_class = 'Modules\\'.$module.'\\Services\\ImportService';
                $import_service = new $path_class();

                $import_service->$method($import_image->data);
            }

            //dd($imports->company->data);
            SessionService::message('token', 'Configurando empresa.');
            $class_method = explode('@', $imports->company->portal_service);
            $module = $class_method[0];
            $method = $class_method[1];

            $path_class = 'Modules\\'.$module.'\\Services\\ImportService';
            $import_service = new $path_class();

            $import_service->$method($imports->company->data);


            SessionService::message('token', 'Atualizando informações no sistema.');
            foreach ($imports->settings as $setting) 
            {
                $class_method = explode('@', $setting->portal_service);
                $module = $class_method[0];
                $method = $class_method[1];

                if(Module::has($module))
                {
                    $path_class = 'Modules\\'.$module.'\\Services\\ImportService';
                    $import_service = new $path_class();

                    Module::has($module);
                    $import_service->$method((array)$setting->data);
                }
            }

            SessionService::message('token', 'Iniciando importação de registros.');
            $validations = collect($imports->validations)->reverse();
            SessionService::widgetsReset('token');
            foreach ($validations as $validation) 
            {
                //SessionService::setWidgetName($validation->portal_service);
                //SessionService::startWidgetNew();
                $class_method = explode('@', $validation->portal_service);
                $module = $class_method[0];
                $method = $class_method[1];

                SessionService::clear($module, $method);
                SessionService::widgetsAdd('token', $module, $method);

                $path_class = 'Modules\\'.$module.'\\Services\\ImportService';
                $import_service = new $path_class();
                $import_service->$method($validation->data);
            }
            SessionService::message('token', 'Importação concluida.');
        } else {
            SessionService::message('token', 'Não foi possível conectar ao portal.');
        }
        SessionService::end('token');
    }

}
