<?php

namespace Modules\DashboardPortal\Http\ViewComposers;

use Modules\Dashboard\Services\ViewComposer\ServiceComposer;
use Modules\ImportWidget\Services\SessionService;

class UpdateComposer extends ServiceComposer 
{

    private $alert;
    private $widgets;

    public function assign($view)
    {
        $this->alert();
        $this->widgets();
    }

    private function alert()
    {
        $this->alert = SessionService::message('token');
    }

    private function widgets()
    {
        $this->widgets = SessionService::widgets('token');
    }    


    public function view($view)
    {
        $view->with('alert', $this->alert);
        $view->with('widgets', $this->widgets);
    }

}