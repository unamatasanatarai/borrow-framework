<?php

class Url
{
	static public function query2obj($query_str)
	{
		$req = explode('&', $query_str);
		$ret = array();
		foreach($req as $twins){
			$twins     = explode('=', $twins);
			$key       = array_shift($twins);
			$ret[$key] = is_array($twins)?implode('=', $twins):null;
		}
		return $ret;
	}

	/**
	 * Get the base URL for the request.
	 * trim_last_folders_count is there because your CMS might be under a different directory than ROOT
	 */
	static public function base($trim = '')
	{
		$host    = 'http' . (env('HTTPS') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
		$request = str_replace($trim, '', $_SERVER['SCRIPT_NAME']);
		return $host . $request;
	}

	static public function current()
	{
		$url = 'http' . (env('HTTPS')?$url .= 's':'') . '://';
		$url .= $_SERVER['HTTP_HOST'];
		if ( ! isset( $_SERVER['REQUEST_URI'] ) )
		{

		    // Microsoft IIS doesn't set REQUEST_URI by default
		    $url .= substr( $_SERVER['PHP_SELF'], 1 );

		    if ( isset( $_SERVER['QUERY_STRING'] ) )
			{
		        $url .= '?' . $_SERVER['QUERY_STRING'];
		    }
		}
		else
		{
		    $url .= $_SERVER['REQUEST_URI'];
		}

		return $url;
	}
}