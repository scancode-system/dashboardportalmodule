<?php

namespace Modules\DashboardPortal\Services;

class SessionService {

	public static  function start($id  = 'token')
	{
		session([$id.'.importing' => true]);
		session()->save();
	}

	public static  function end($id  = 'token')
	{
		session([$id.'.importing' => false]);
		session()->save();
	}

	public static  function importing($id  = 'token')
	{
		return session($id.'.importing', false);
	}

	public static  function getMessage($id  = 'token')
	{
		return session($id.'.message', 'N/A');
	}

	public static  function setMessage($string, $id = 'token')
	{
		session([$id.'.message' => $string]);
		session()->save();
	}

	public static  function clear($id = 'token')
	{
		session([$id.'.widgets' => []]);
		session()->save();
	}

	public static  function add($widget_name, $id = 'token')
	{
		$widgets = self::widgets();
		$widgets->put($widget_name, [
			'name' => $widget_name,
			'new' => 0,
			'updated' => 0,
			'failures' => 0,
			'completed' => 0
		]);

		session([$id.'.widgets' => $widgets->toArray()]);
		session()->save();
	}

	public static  function widgets($id = 'token')
	{
		return collect(session($id.'.widgets', []));
	}

	public static  function new($widget_name, $add = null, $id = 'token')
	{
		if($add){
			$widgets = self::widgets();
			$widget =  $widgets->pull($widget_name);
			$widget['new']++;
			$widgets->put($widget_name, $widget);
			session([$id.'.widgets' => $widgets->toArray()]);
			session()->save();
		} 
		return collect(session($id.'.widgets', []));
	}

	public static  function updated($widget_name, $add = null, $id = 'token')
	{
		if($add){
			$widgets = self::widgets();
			$widget =  $widgets->pull($widget_name);
			$widget['updated']++;
			$widgets->put($widget_name, $widget);
			session([$id.'.widgets' => $widgets->toArray()]);
			session()->save();
		} 
		return collect(session($id.'.widgets', []));
	}

	public static  function failures($widget_name, $add = null, $id = 'token')
	{
		if($add){
			$widgets = self::widgets();
			$widget =  $widgets->pull($widget_name);
			$widget['failures']++;
			$widgets->put($widget_name, $widget);
			session([$id.'.widgets' => $widgets->toArray()]);
			session()->save();
		} 
		return collect(session($id.'.widgets', []));
	}

	public static function completed($widget_name, $completed, $id = 'token')
	{
		$widgets = self::widgets();
		$widget =  $widgets->pull($widget_name);
		$widget['completed'] = floor($completed);
		$widgets->put($widget_name, $widget);
		session([$id.'.widgets' => $widgets->toArray()]);
		session()->save();
		return collect(session($id.'.widgets', []));
	}


/*
	public static  function setWidgetName($widget_name, $id = 'token')
	{
		session([$id.'.widget.name' => $widget_name]);
		session()->save();
	}

	public static  function getWidgetName($id = 'token')
	{
		return session($id.'.widget.name', null);
	}

	public static  function startWidgetNew($id = 'token')
	{
		session([$id.'.widget.new' => 0]);
		session()->save();
	}

	/*public static  function addWidgetNew($id = 'token')
	{

		session([$id.'.widget.new' => (self::getWidgetNew()+1)]);
		session()->save();
	}

	public static  function getWidgetNew($id = 'token')
	{
		return session($id.'.widget.new', 0);
	}*/

/*    public static  function addWidget($widget_name, $id = 'token')
    {

        session([$id.'.widget.name' => $widget_name]);
    }

    public static  function getWidgets($widget_name, $id = 'token')
    {
    	
        session([$id.'.widget.name' => $widget_name]);
    }*/

}
