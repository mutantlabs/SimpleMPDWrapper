<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keys Controller
 *
 * This is a basic Key Management REST controller to make and delete keys.
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php
require(APPPATH.'/libraries/REST_Controller.php');

class Api extends REST_Controller
{  
	/* 
	 * lets set some auth levels for public methods
	 * protected $methods = array(
			'index_get' => array('level' => 1),
			'add_post' => array('level' => 5, 'limit' => 10),
	); */
	public function __construct()
	{
		parent::__construct();

	}

	function index_get()
	{
		//this should be replaced with the an API glossary - OR lets use swagger docs?
		$data['message'] = "Warm Pi";
		$code = 200;
		$this->response($data, $code);
	}
	

      
}

?>