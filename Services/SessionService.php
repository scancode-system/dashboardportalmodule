<?php

namespace Modules\DashboardPortal\Services;

class SessionService {

    public static  function getMessage($id  = 'token')
    {
        return session($id.'.message', 'N/A');
    }

    public static  function setMessage($string, $id = 'token')
    {
        session([$id.'.message' => $string]);
    }

}
