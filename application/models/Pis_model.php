<?php 
	
	class Pis_model extends CI_Model
	{


		public function __construct() {
	        header('Access-Control-Allow-Origin: *');
	    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    	parent::__construct();
		}

		public function getAllFieldOffices($payload)
		{	
			#print_r($payload);
			$pis_db = $this->db;
			if($payload->USER_LEVEL_ID == 1 or $payload->USER_LEVEL_ID == 2 or $payload->FIELD_OFFICE == 'Central Office HQ'){
				$pis_db->order_by('NAME', 'ASC');
				$sql = $pis_db->get('field_office');	
			}else if($payload->USER_LEVEL_ID == 3 or $payload->USER_LEVEL_ID == 6 or $payload->USER_LEVEL_ID == 7 or $payload->USER_LEVEL_ID == 10){
				$pis_db->where('NAME', $payload->FIELD_OFFICE );
				$query = $pis_db->get('field_office');
				$data1 = $query->row();
				#print_r($data1);
				//@TODO
				$pis_db->reconnect();
				$pis_db->where('REGION', $data1->REGION );;
				$sql = $pis_db->get('field_office');	



			}else if($payload->USER_LEVEL_ID == 4 or $payload->USER_LEVEL_ID == 5){
				$pis_db->where('NAME', $payload->FIELD_OFFICE );
				$sql = $pis_db->get('field_office');
			}else{
				$sql = $pis_db->get('field_office');	
			}
			
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING DATA',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}

		public function getAllFieldOffices2()
		{
			$pis_db = $this->load->database('pis', TRUE);
			#$pis_db->order_by('REGION', 'asc');
			$sql = $pis_db->get('field_office');
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING DATA',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}

		public function getFieldOfficeByFieldID($payload)
		{
			if($payload->field_id != null)
			{
				$pis_db = $this->load->database('pis', TRUE);
				$sql = $pis_db->get('field_office', array('ID' => $payload->field_id));
				if($sql->num_rows() > 0 )
				{
					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS FETCHING FIELD OFFICE',
						'payload' => $sql->row()
					);
				}
				else
				{
					$response = array(
						'status' => 'ERROR',
						'message' => 'ERRO FETCHING FIELD OFFICE'
					);
				}
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'INVALID PARAMETER'
				);
			}
			return json_encode($response);
		}


		public function fetchAllRegion2()
		{
			$pis_db = $this->db;
			$pis_db->WHERE('CATEGORY_', 'REGION');
			$pis_db->order_by('VALUE_', 'ASC');
			#$pis_db->limit(100);
			$sql = $pis_db->get('system_codes');
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REGION',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}


		public function fetchAllFieldOfficeRegion()
		{
			$sql = $this->db->query("SELECT reg.VALUE_ as `REGION_NAME`,fo.* FROM field_office fo
				LEFT JOIN system_codes reg on fo.REGION = reg.ID
				ORDER BY reg.VALUE_ ASC");
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REGION',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}


		public function fetchAllRegion()
		{
			$pis_db = $this->load->database('pis', TRUE);
			$pis_db->like('CATEGORY_', 'REGION');
			#$pis_db->limit(100);
			$sql = $pis_db->get('system_codes');
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REGION',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}


		public function fetchFieldOfficeByRegion($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);
			$pis_db->select('NAME');
			$pis_db->where('REGION', $payload->REGION);
			$sql = $pis_db->get('field_office');
			if($sql->num_rows() > 0 )
			{
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING DATA',
					'payload' => $sql->result()
				);
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'message' => 'NO DATA FOUND'
				);
			}
			return json_encode($response);
		}



		public function getDocketDataByDocketNo($payload)
		{
			if(isset($payload->docket_no) && !empty($payload->docket_no))
			{
				$pis_db = $this->load->database('pis', TRUE);
				$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, PETITIONER as PETITIONER_ID, db.COURT_OF_ORIGIN, db.IS_MILITARY_COURT, db.OFFENSE, db.docket_submitted_date, db.docket_disposed_date, db.COURT_ORDER_DATE, pj.due_date , pj.RECEIVED_DATE, fo.NAME as FIELD_OFFICE, cp.CASE_NO, sc.VALUE_ as OFFICE_FINDINGS, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, db.REMARKS, sc2.VALUE_ as SENTENCE, ug2.NAME as POSITION, db.OTHER_STATUS as STATUS REMARKS, sc3.VALUE_ as COURT_DISPOSITION");
				$pis_db->from('docket_book as db');
				$pis_db->join('petitioner_profile as pp', 'db.PETITIONER = pp.ID', 'left');
				$pis_db->join('probation_job as pj', 'pj.ID = db.ID', 'left');
				$pis_db->join('field_office as fo', 'pp.FIELD_OFFICE = fo.ID', 'left');
				$pis_db->join('case_profile as cp', 'pp.CASE_PROFILE = cp.ID', 'left');
				$pis_db->join('system_codes as sc', 'db.OFFICE_FINDINGS = sc.ID AND db.SENTENCE = sc.ID', 'left');
				$pis_db->join('system_codes as sc2', 'db.SENTENCE = sc2.ID', 'left');
				$pis_db->join('system_codes as sc3', 'db.DOCKET_STATUS = sc3.ID', 'left');
				$pis_db->join('user_profile as up', 'pj.ASSIGNED_TO = up.ID', 'left');
				$pis_db->join('user_group as ug', 'up.ID = ug.USER_ID', 'left');
				$pis_db->join('usergroup as ug2', 'ug.GROUP_ID = ug2.ID', 'left');
				$pis_db->where('db.DOCKET_NO', $payload->docket_no);
				$get = $pis_db->get('');
				if($get->num_rows() > 0 )
				{
					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS FETCHING DATA',
						'payload' => $get->row()
					);
				}
				else
				{
					$response = array(
						'status' => 'ERROR',
						'message' => 'DATA NOT FOUND'
					);
				}
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'payload' => 'INVALID PARAMETER PASS/ EMPTY PARAMETER'
				);
			}
			return json_encode($response);
		}

		public function getDocketDataByDocketID($payload)
		{
			if(isset($payload->docket_id) && !empty($payload->docket_id))
			{
				$pis_db = $this->load->database('pis', TRUE);
				$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, db.COURT_OF_ORIGIN, db.IS_MILITARY_COURT, db.OFFENSE, db.docket_submitted_date, db.docket_disposed_date, db.COURT_ORDER_DATE, pj.due_date , pj.RECEIVED_DATE, fo.NAME as FIELD_OFFICE, cp.CASE_NO, sc.VALUE_ as OFFICE_FINDINGS, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, db.REMARKS, sc2.VALUE_ as SENTENCE, ug2.NAME as POSITION, db.OTHER_STATUS as STATUS REMARKS, sc3.VALUE_ as COURT_DISPOSITION");
				$pis_db->from('docket_book as db');
				$pis_db->join('petitioner_profile as pp', 'db.PETITIONER = pp.ID', 'left');
				$pis_db->join('probation_job as pj', 'pj.ID = db.ID', 'left');
				$pis_db->join('field_office as fo', 'pp.FIELD_OFFICE = fo.ID', 'left');
				$pis_db->join('case_profile as cp', 'pp.CASE_PROFILE = cp.ID', 'left');
				$pis_db->join('system_codes as sc', 'db.OFFICE_FINDINGS = sc.ID AND db.SENTENCE = sc.ID', 'left');
				$pis_db->join('system_codes as sc2', 'db.SENTENCE = sc2.ID', 'left');
				$pis_db->join('system_codes as sc3', 'db.DOCKET_STATUS = sc3.ID', 'left');
				$pis_db->join('user_profile as up', 'pj.ASSIGNED_TO = up.ID', 'left');
				$pis_db->join('user_group as ug', 'up.ID = ug.USER_ID', 'left');
				$pis_db->join('usergroup as ug2', 'ug.GROUP_ID = ug2.ID', 'left');
				$pis_db->where('db.ID', $payload->docket_id);
				$get = $pis_db->get('');
				if($get->num_rows() > 0 )
				{
					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS FETCHING DATA',
						'payload' => $get->row()
					);
				}
				else
				{
					$response = array(
						'status' => 'ERROR',
						'message' => 'DATA NOT FOUND'
					);
				}
			}
			else
			{
				$response = array(
					'status' => 'ERROR',
					'payload' => 'INVALID PARAMETER PASS/ EMPTY PARAMETER'
				);
			}
			return json_encode($response);
		}


		public function updateDocketBook($payload)
		{
			#print_r($payload);
			$pis_db = $this->load->database('pis', TRUE);

			$update_data = array();			
			if(isset($payload->docket_submitted_date) && $payload->docket_submitted_date != "")
			{
				$update_data = array_merge($update_data,  array("docket_submitted_date"=>$payload->docket_submitted_date));
				
			}else{
				$update_data = array_merge($update_data,  array("docket_submitted_date"=>null));
			}

			if(isset($payload->docket_disposed_date) && $payload->docket_disposed_date != "")
			{
				$update_data = array_merge($update_data,  array("docket_disposed_date"=>$payload->docket_disposed_date));
				
			}else{
				$update_data = array_merge($update_data,  array("docket_disposed_date"=>null));
			}


			
			#print_r($update_data);
			$pis_db->where('docket_no', $payload->docket_no);
			$update = $pis_db->update('docket_book', $update_data);
			if($update){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS UPDATTING DOCKET BOOK',

				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR UPDATING DOCKET BOOK'
				);
				$error = $pis_db->error(); // Has keys 'code' and 'message'

	           	print_r($error);

			}
		
			return json_encode($response);
		}


		
		public function referralsReceived($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);

			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, cp.CASE_NO, cp.COURT, db.OFFENSE, cp.SENTENCE, db.COURT_ORDER_DATE AS DATE_OF_COURT_ORDER, pj.RECEIVED_DATE as RECEIVED_DATE, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("case_profile as cp", "pp.CASE_PROFILE = cp.ID", "left");
			$pis_db->join("probation_job as pj", "pj.ID = db.ID", "left");
			$pis_db->join("user_profile as up", "db.OFFICER = up.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID");
			$pis_db->like("db.DOCKET_NO", "PI", "after");
			$pis_db->like("pj.RECEIVED_DATE", $date);
			//$pis_db->LIMIT(2);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get("");
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS RECEIVED',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS RECEIVED',
				);
			}
			return json_encode($response);
		}

		public function referralsCPIReceived($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);

			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, cp.CASE_NO, cp.COURT, db.OFFENSE, cp.SENTENCE, db.COURT_ORDER_DATE AS DATE_OF_COURT_ORDER, pj.RECEIVED_DATE as RECEIVED_DATE, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("case_profile as cp", "pp.CASE_PROFILE = cp.ID", "left");
			$pis_db->join("probation_job as pj", "pj.ID = db.ID", "left");
			$pis_db->join("user_profile as up", "db.OFFICER = up.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID");
			$pis_db->like("db.DOCKET_NO", "CPI");
			$pis_db->like("pj.RECEIVED_DATE", $date);
			//$pis_db->LIMIT(2);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get("");
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS RECEIVED',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS RECEIVED',
				);
			}
			return json_encode($response);
		}


		public function referralsPSReceived($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);

			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, cp.CASE_NO, cp.COURT, db.OFFENSE, cp.SENTENCE, db.COURT_ORDER_DATE AS DATE_OF_COURT_ORDER, pj.RECEIVED_DATE as RECEIVED_DATE, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`, db.REFERRAL_TYPE, db.CASE_CLASSIFICATION, db.PROBATION_START,db.PROBATION_END ");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("case_profile as cp", "pp.CASE_PROFILE = cp.ID", "left");
			$pis_db->join("probation_job as pj", "pj.ID = db.ID", "left");
			$pis_db->join("user_profile as up", "db.OFFICER = up.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID", "left");
			$pis_db->like("db.DOCKET_NO", "PS", "after");
			$pis_db->like("pj.RECEIVED_DATE", $date);
			//$pis_db->LIMIT(2);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get("");
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS RECEIVED',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS RECEIVED',
				);
			}
			return json_encode($response);
		}


		public function referralsCPSReceived($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);

			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS PETITIONER_NAME, cp.CASE_NO, cp.COURT, db.OFFENSE, cp.SENTENCE, db.COURT_ORDER_DATE AS DATE_OF_COURT_ORDER, pj.RECEIVED_DATE as RECEIVED_DATE, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ', up.MIDDLENAME) as INVESTIGATING_OFFICER, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`, db.REFERRAL_TYPE, db.CASE_CLASSIFICATION, db.PROBATION_START,db.PROBATION_END ");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("case_profile as cp", "pp.CASE_PROFILE = cp.ID", "left");
			$pis_db->join("probation_job as pj", "pj.ID = db.ID", "left");
			$pis_db->join("user_profile as up", "db.OFFICER = up.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID", "left");
			$pis_db->like("db.DOCKET_NO", "CPS", "after");
			$pis_db->like("pj.RECEIVED_DATE", $date);
			//$pis_db->LIMIT(2);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get("");
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS RECEIVED',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS RECEIVED',
				);
			}
			return json_encode($response);
		}


		public function referralsActedUpon($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);
			//$date = $payload->year.'-'.$payload->month;
			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS CLIENT, pj.RECEIVED_DATE, db.DAYS_LEFT, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ',up.MIDDLENAME) as ASSIGNED_TO, CONCAT(up2.LASTNAME,', ', up2.FIRSTNAME,' ',up2.MIDDLENAME) as INVESTIGATING_OFFICER, sc.VALUE_ as OFFICE_FINDINGS, db.docket_submitted_date, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("psir_profile as psir", "db.PSIR = psir.ID", "left");
			$pis_db->join("probation_job as pj", "db.ID = pj.ID", "left");
			$pis_db->join("user_profile as up", "pj.ASSIGNED_TO = up.ID", "left");
			$pis_db->join('system_codes as sc', 'db.OFFICE_FINDINGS = sc.ID', 'left');
			$pis_db->join("user_profile as up2", "db.OFFICER = up2.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID", "left");
			$pis_db->like("db.docket_submitted_date", $date);
			//$pis_db->where("db.IS_PSIR_COMPLETE", 1);
			$pis_db->where('db.OFFICE_FINDINGS is not NULL', NULL, FALSE);
			//$pis_db->where("db.DOCKET_STATUS", 2372);
			$pis_db->like("db.DOCKET_NO", "PI", "after");
			//$pis_db->LIMIT(1);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get('');
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS ACTED UPON',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS ACTED UPON',
				);
			}
			return json_encode($response);
		}

		public function caseDisposed($payload)
		{
			$pis_db = $this->load->database('pis', TRUE);
			//$date = $payload->year.'-'.$payload->month;
			$date = $payload->year;
			if(isset($payload->month)){
				$date .= '-'.$payload->month;
			}
			$pis_db->select("db.DOCKET_NO, CONCAT(pp.LAST_NAME,', ', pp.FIRST_NAME,' ',pp.MIDDLE_NAME) AS CLIENT, pj.RECEIVED_DATE, db.DAYS_LEFT, CONCAT(up.LASTNAME,', ', up.FIRSTNAME,' ',up.MIDDLENAME) as ASSIGNED_TO, CONCAT(up2.LASTNAME,', ', up2.FIRSTNAME,' ',up2.MIDDLENAME) as INVESTIGATING_OFFICER, sc.VALUE_ as OFFICE_FINDINGS, db.docket_submitted_date, db.docket_disposed_date, ds.VALUE_ as `disposed_decision`, pp.FIELD_OFFICE as `FIELD_OFFICE_ID` , fo.NAME as `FIELD_OFFICE`");
			$pis_db->from("docket_book as db");
			$pis_db->join("petitioner_profile as pp", "db.PETITIONER = pp.ID", "left");
			$pis_db->join("psir_profile as psir", "db.PSIR = psir.ID", "left");
			$pis_db->join("probation_job as pj", "db.ID = pj.ID", "left");
			$pis_db->join("user_profile as up", "pj.ASSIGNED_TO = up.ID", "left");
			$pis_db->join('system_codes as sc', 'db.OFFICE_FINDINGS = sc.ID', 'left');
			$pis_db->join('system_codes as ds', 'db.DOCKET_STATUS = ds.ID', 'left');
			$pis_db->join("user_profile as up2", "db.OFFICER = up2.ID", "left");
			$pis_db->join("field_office as fo", "pp.FIELD_OFFICE = fo.ID", "left");
			$pis_db->like("db.docket_disposed_date", $date);
			//$pis_db->where("db.IS_PSIR_COMPLETE", 1);

			$pis_db->where('db.OFFICE_FINDINGS is not NULL',NULL, FALSE);
			$pis_db->where_in("db.DOCKET_STATUS", array(2364,2365,2366,2367,2368,2369));
			$pis_db->like("db.DOCKET_NO", "PI", "after");
			//$pis_db->LIMIT(1);
			if(isset($payload->field_office) && !empty($payload->field_office) && $payload->field_office != "ALL")
			{
				$pis_db->where('fo.NAME', $payload->field_office);
			}
			$sql = $pis_db->get('');
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING REFERRALS ACTED UPON',
					'payload' => $sql->result()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING REFERRALS ACTED UPON',
				);
			}
			return json_encode($response);
		}

		public function getDocketInvID($payload){
			$pis_db = $this->load->database('pis', TRUE);
			
			
			$pis_db->select("db.ID");
			$pis_db->from("docket_book as db");
			
			$pis_db->where("db.DOCKET_NO", $payload->docket_no);
			$sql = $pis_db->get('');
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING DOCKET INV ID',
					'payload' => $sql->row()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING DOCKET INV ID',
				);
			}
			return json_encode($response);
		}
		
		public function getFactSheet($payload){
			$pis_db = $this->load->database('pis', TRUE);
			
			
			$pis_db->select("db.PETITIONER");
			$pis_db->from("docket_book as db");
			
			$pis_db->where("db.DOCKET_NO", $payload->docket_no);
			$sql = $pis_db->get('');
			if($sql->num_rows() > 0 ){
				$response = array(
					'status' => 'SUCCESS',
					'message' => 'SUCCESS FETCHING DOCKET INV ID',
					'payload' => $sql->row()
				);
			}else{
				$response = array(
					'status' => 'ERROR',
					'message' => 'ERROR FETCHING DOCKET INV ID',
				);
			}
			return json_encode($response);

		}




	}



?>