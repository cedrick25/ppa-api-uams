<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmis extends CI_Controller {


	public function __construct() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    	parent::__construct();
	}

	public function upsertMasterlist()
	{
		include APPPATH . 'third_party/ssp.php';
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Cmis_Probationer_model->upsertMasterlist($payload);
	}


	public function upsertFeedback()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Cmis_Feedback_model->upsertFeedback($payload);
	}
	

	public function widgets()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Cmis_Widgets_model->widgets($payload);
	}

	public function callProcedure()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Cmis_Widgets_model->callProcedure($payload->procedure,$payload);
	}

}
