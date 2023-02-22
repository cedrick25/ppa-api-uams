<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pis extends CI_Controller {


	public function __construct() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    	parent::__construct();
	}

	
	public function getAllFieldOffices()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getAllFieldOffices($payload);
	}
	
	public function fetchAllFieldOfficeRegion()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->fetchAllFieldOfficeRegion($payload);
	}
	public function getAllFieldOffices2()
	{
		echo $this->Pis_model->getAllFieldOffices2();
	}

	public function getFieldOfficeByFieldID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getFieldOfficeByFieldID($payload);
	}

	public function fetchAllRegion()
	{
		echo $this->Pis_model->fetchAllRegion();
	}

	public function fetchAllRegion2()
	{
		echo $this->Pis_model->fetchAllRegion2();
	}

	public function fetchFieldOfficeByRegion()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->fetchFieldOfficeByRegion($payload);
	}


	public function getDocketDataByDocketNo()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getDocketDataByDocketNo($payload);
	}

	public function getDocketDataByDocketID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getDocketDataByDocketID($payload);
	}

	public function referralsReceived()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->referralsReceived($payload);
	}

	public function referralsPSReceived()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->referralsPSReceived($payload);
	}

	public function referralsActedUpon()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->referralsActedUpon($payload);
	}

	public function caseDisposed()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->caseDisposed($payload);
	}

	public function referralsCPIReceived()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->referralsCPIReceived($payload);
	}

	public function referralsCPSReceived()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->referralsCPSReceived($payload);
	}

	public function updateDocketBook()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->updateDocketBook($payload);
	}

	
	public function getDocketInvID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getDocketInvID($payload);
	}

	public function getFactSheet()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Pis_model->getFactSheet($payload);
	}


}
