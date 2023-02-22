<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends CI_Controller {


	
	public function index()
	{
		$this->load->view('version');
	}
	
}
