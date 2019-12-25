<?php

namespace Modules\DashboardPortal\Services;

use Modules\DashboardPortal\Services\PortalApiService;
use Modules\DashboardPortal\Services\SessionService;
use Modules\DashboardPortal\Services\FileService;
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
            SessionService::setMessage('Token Inválido.');
            throw new Exception("Token inválido");
        }
    }

    public function import() {
        SessionService::setMessage('Conectando ao portal.');
        $portal_api_service = new PortalApiService($this->login, $this->password, $this->event);
        if($portal_api_service->check())
        {
            SessionService::setMessage('Buscando dados do evento.');
            $import_services = $portal_api_service->event();
            
            SessionService::setMessage('Fazendo Download de arquivos e imagens.');
            FileService::storage($portal_api_service->download());
            
            foreach ($import_service as $import_service) {
                SessionService::setMessage($import_service->message);
            }
        } else {
            SessionService::setMessage('Não foi possível conectar ao portal.');
        }
    }

    public function data($view){}

}
