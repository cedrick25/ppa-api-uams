<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {


	public function __construct() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    	parent::__construct();
	}

	
	
	public function getAuditTrail()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAuditTrail($payload);

	}
	public function doLogout()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->doLogout($payload);

	}
	public function getUserPosition()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getUserPosition($payload);

	}

	public function getIS_USERS_INFO()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getIS_USERS_INFO($payload);

	}

	public function backup()
	{	
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->backup();
	}

	public function backupDate()
	{	
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->backupDate();
	}
	public function full_restore()
	{	
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->full_restore();
	}

	
	public function getDatetime()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getDatetime($payload);
	}

	

	
	public function getAllUserType()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAllUserType($payload);
	}

	public function getAllUserTypes()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAllUserTypes($payload);
	}

	public function AddUserType()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->AddUserType($payload);
	}	
	public function AddUserPosition()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->AddUserPosition($payload);
	}	
	public function AddFieldOffice()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->AddFieldOffice($payload);
	}	
	public function UpdateFieldOffice()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->UpdateFieldOffice($payload);
	}	

	public function getAllUserTypeModules()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAllUserTypeModules($payload);
	}
	public function addIS()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->addIS($payload);
	}
	public function getIS()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getIS($payload);
	}

	public function getISByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getISByID($payload);
	}
	public function getFieldOfficeByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getFieldOfficeByID($payload);
	}
	public function getIS_Users()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getIS_Users($payload);
	}

	public function getISUserByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getISUserByID($payload);
	}public function getIS_Users_Status()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getIS_Users_Status($payload);
	}
	public function upsertIS_User()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->upsertIS_User($payload);
	}

	public function updateISbyID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->updateISbyID($payload);
	}

	
	public function authenticate()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->authenticate($payload);
	}

	public function AddUser()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->AddUser($payload);
	}	
	public function UpdateUser()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->UpdateUser($payload);
	}	
	public function UpdateUserType()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->UpdateUserType($payload);
	}		

	
	public function getAllUserList()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAllUserList($payload);
	}

	public function getUserByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getUserByID($payload);
	}
	public function getUserTypeByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getUserTypeByID($payload);
	}

	public function getUserPositionByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getUserPositionByID($payload);
	}
	public function UpdateUserPosition()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->UpdateUserPosition($payload);
	}
	public function getUserTypeByModulesByID()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getUserTypeByModulesByID($payload);
	}

	public function getDeletedList()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getDeletedList($payload);
	}
	public function restoreDeleted()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->restoreDeleted($payload);
	}

	

	public function caseload_reports()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->caseload_reports($payload);
	}

	public function getAllforms()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getAllforms($payload);
	}
	public function getFormByPage()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->getFormByPage($payload);
	}

	public function UpdateForm()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->UpdateForm($payload);
	}
	
	public function cleanUp()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->cleanUp($payload);
	}

	public function email()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->Email($payload);
	}

	public function insertSMSManually()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->API_model->insertSMSManually($payload);
	}
}
