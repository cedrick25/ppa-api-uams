<?php 
	
class Cmis_Feedback_model extends CI_Model
{


	public function __construct() {
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	parent::__construct();
	}

	public function getIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   
		  {
		    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
		  }
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
		  {
		    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		  }
		//whether ip is from remote address
		else
		  {
		    $ip_address = $_SERVER['REMOTE_ADDR'];
		  }
		return $ip_address;
	}

	public function AuditInsert($payload)
	{
		$data = array();
		$ip = $this->getIP();
		$payload->created_date = date("Y-m-d H:i:s");
		$payload->IP_ADDRESS = $ip;
		$fields = array('action','created_date','created_by','module','IP_ADDRESS');
		foreach($payload as $key => $value)
		{
			if($value != null && in_array($key, $fields))
			{
				$data = array_merge($data, array($key => $value));
			}
		}
		$insert = $this->db->insert("audit_trail", $data);
		if($insert)
		{
			$response = array(
				'status' => 'SUCCESS',
				'message' => 'SUCCESS INSERTING DATA'
			);
		}
		else
		{
			$response = array(
				'status' => 'ERROR',
				'message' => 'ERROR INSERTING DATA!'
			);
		}
		return json_encode($response);
	}


	public function upsertFeedback($payload)
	{
		if($payload != null)
		{
			switch ($payload->method) {
				case 'insert':
					$data = array();
					$payload->created_date = date("Y-m-d H:i:s");
					$fields = array('form','date','field','created_by','message','seen','done','created_date');
					foreach($payload as $key => $value)
					{
						if($value != null && in_array($key, $fields))
						{
							$data = array_merge($data, array($key => $value));
						}
					}
					$insert = $this->db->insert("feedback", $data);
					if($insert)
					{
						$response = array(
							'status' => 'SUCCESS',
							'message' => 'SUCCESS INSERTING DATA'
						);
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'ERROR INSERTING DATA!'
						);
					}
					break;
				case 'update':
					$required_param = 1;
					$fields = array('form','date','field','created_by','message','seen','done','created_date');
					foreach ($payload as $key => $value) {
						if(in_array($key, $fields))
						{
							$this->db->set("".$key."", $value);
						}
					}
					if(isset($payload->feedback_id) && $payload->feedback_id != null )
					{
						$this->db->where('feedback_id', $payload->feedback_id);
						$required_param--;
					}
					if($required_param == 0)
					{
						$update = $this->db->update('feedback');
						if($this->db->affected_rows() > 0)
						{
							$response = array(
								'status' => 'SUCCESS',
								'message' => 'DATA HAS BEEN UPDATED!'
							);
						}
						else
						{
							$response = array(
								'status' => 'ERROR',
								'message' => 'NO DATA HAS BEEN UPDATED!'
							);
						}
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'PLEASE FILL UP ALL THE REQURIED FIELDS'
						);
					}
					break;
				case 'count':
					$fields = array('LASTNAME', 'FIRSTNAME', 'MIDDLENAME', 'ALIAS', 'SUPVOFFICE', 'REMARKS', 'SDOCKETNO', 'YEAR', 'REGION', 'STARTMM', 'STARTDD', 'STARTYY', 'ENDMM', 'ENDDD', 'ENDYY', 'STATUS');
					foreach ($payload as $key => $value) {
						if($value != null && in_array($key, $fields))
						{
							$this->db->where($key, $value);
						}
					}
					$get = $this->db->get('feedback');
					if($get->num_rows() > 0 )
					{
						$response = array(
							'status' => 'SUCCESS',
							'message' => 'SUCCESSFULLY FETCHED DATA',
							'count' => count($get->result())
						);
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'DATA NOT FOUND',
						);
					}
					break;
				case 'fetchAll':
					$fields = array('feedback_id','form','date','field','created_by','message','seen','created_date');
					foreach ($payload as $key => $value) {
						if($value != null && in_array($key, $fields))
						{
							$this->db->where($key, $value);
						}
					}
					$get = $this->db->get('feedback');
					if($get->num_rows() > 0 )
					{
						$response = array(
							'status' => 'SUCCESS',
							'message' => 'SUCCESSFULLY FETCHED DATA',
							'payload' => $get->result()
						);
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'DATA NOT FOUND',
						);
					}
					break;
				case 'fetchByID':
					$required_param = 1;

					if(isset($payload->id) && $payload->id != null )
					{
						$this->db->where('id', $payload->id);
					}
					$get = $this->db->get('feedback');
					if($get->num_rows() > 0)
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
					break;
				case 'fetchByFormID':
					$required_param = 1;
					$form = $payload->form;
					$date = $payload->date;
					$field = $payload->field;
					
					$query = $this->db->query("CALL getFeedbackbyform('".$form."','".$date."','".$field."')");
					if($query->num_rows() > 0)
					{
						$response = array(
							'status' => 'SUCCESS',
							'message' => 'SUCCESS FETCHING DATA',
							'payload' => $query->result()
						);
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'DATA NOT FOUND'
						);
					}
					break;
				
				case 'fetchByFormID2':
					$required_param = 1;
					$this->db->select("f.*, u.USER_FULLNAME, u.FIELD_OFFICE");
					if(isset($payload->form) && $payload->form != null )
					{
						$this->db->where('f.form', $payload->form);
					}
					if(isset($payload->date) && $payload->date != null )
					{
						$this->db->where('f.date', $payload->date);
					}
					if(isset($payload->field) && $payload->field != null )
					{
						$this->db->where('f.field', $payload->field);
					}
					if(isset($payload->seen) && $payload->seen != null )
					{
						$this->db->where('f.seen', $payload->seen);
					}
					$this->db->from('feedback as f');
					$this->db->join('USERS as u', 'f.created_by = u.USER_ID');
					$get = $this->db->get('');

					if($get->num_rows() > 0)
					{
						$response = array(
							'status' => 'SUCCESS',
							'message' => 'SUCCESS FETCHING DATA',
							'payload' => $get->result()
						);
					}
					else
					{
						$response = array(
							'status' => 'ERROR',
							'message' => 'DATA NOT FOUND'
						);
					}
					break;
				default:
					$response = array(
						'status' => 'ERROR',
						'message' => 'METHOD CANNOT BE EMPTY!'
					);
					break;
			}
		}
		else
		{
			$response = array(
				'status' => 'ERROR',
				'message' => 'PLEASE CHECK YOUR DATA'
			);
		}
		return json_encode($response);
	}

}