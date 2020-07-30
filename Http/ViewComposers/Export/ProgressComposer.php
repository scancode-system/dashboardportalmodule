<?php

namespace Modules\DashboardPortal\Http\ViewComposers\Export;

use Modules\Dashboard\Services\ViewComposer\ServiceComposer;
use Modules\ImportWidget\Services\SessionService;
use Modules\DashboardPortal\Services\Export\PortalUpdateService;

class ProgressComposer extends ServiceComposer 
{

    private $message;
    private $processing;

    public function assign($view)
    {
        $this->message();
        $this->processing();
    }

    private function message()
    {
        $this->message = PortalUpdateService::message();
    }

    private function processing()
    {
        $this->processing = !PortalUpdateService::status();
    }    


    public function view($view)
    {
        $view->with('message', $this->message);
        $view->with('processing', $this->processing);
    }

}