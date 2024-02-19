<?php 
	
	class API_model extends CI_Model
	{


		public function __construct() {
	        header('Access-Control-Allow-Origin: *');
	    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    	header("Content-type: application/json");
	    	parent::__construct();
		}

		function backup(){
			$fileName='db_backup.sql.zip';
			ini_set('memory_limit', '-1');
		    // Load the DB utility class
		    $this->load->dbutil();
			  $prefs = array(
		                      // List of tables to omit from the backup
		        'format'        => 'txt',                       // gzip, zip, txt
		        'filename'      => 'db_backup.sql',              // File name - NEEDED ONLY WITH ZIP FILES
		        'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
		        'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
		        'newline'       => "\n"                         // Newline character used in backup file
		);
		    // Backup your entire database and assign it to a variable
		    $backup =& $this->dbutil->backup();
		   
		    // Load the file helper and write the file to your server
		    $this->load->helper('file');
		    write_file(FCPATH.'/downloads/'.$fileName, $backup);

		    // Load the download helper and send the file to your desktop
		    $this->load->helper('download');
		    force_download($fileName, $backup);
		}

		function full_restore(){

			#$fileName='db_backup.zip';
			ini_set('memory_limit', '-1');
		    // Load the DB utility class
		    $sql_contents = file_get_contents($_FILES['fileToUpload']['tmp_name']);
		    #var_dump($_FILES);
		    echo "LIST OF QUERIES BEING EXECUTED";
		    $sql_contents = explode(";", $sql_contents);

		    foreach($sql_contents as $query)
		    {

		        $pos = strpos($query,'ci_sessions');
		        var_dump($pos);
		        echo $query;
		        if($pos == false)
		        {
		            #$result = $this->db->query($query);
		        }
		        else
		        {
		            continue;
		        }

		    }
		}

		function generateSQL($table_name,$csv_data){
			$table_name = $table_name;
            $csv_data   = $csv_data;
            $csv_array    = explode("\n",$csv_data);
            $column_names = explode(",",$csv_array[0]);
 
            // Generate base query
            $base_query = "INSERT INTO `$table_name` (";
            $first      = true;
            foreach($column_names as $column_name)  
            {
                if(!$first)
                    $base_query .= ", ";    
                $column_name = trim($column_name);
                $base_query .= "`$column_name`";
                $first = false;
            }
            $base_query .= ") ";

            $last_data_row = count($csv_array) - 1;
            for($counter = 1; $counter < $last_data_row; $counter++)
            {
                $value_query = "VALUES (";
                $first = true;
                $data_row = explode(",",$csv_array[$counter]);
                $value_counter = 0;
                foreach($data_row as $data_value)   
                {
                    if(!$first)
                        $value_query .= ", ";   
                    $data_value = trim($data_value);
                    $value_query .= "'$data_value'";
                    $first = false;
                }
                $value_query .= ")";
        
                // Combine generated queries to generate final query
                $query = $base_query .$value_query .";";
            	return $query;
                
            }
		}

		function backupDate(){
			$start = $_POST['start'];
			$end = $_POST['end'];

			$fileName=$start."_".$end."_backup.sql";
			#echo $fileName;
			$this->load->dbutil();
			$this->load->library('zip');
			
			$query = $this->db->query("SELECT * FROM F5T1 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			#echo $result;
			$q = $this->generateSQL("F5T1",$result);
			#echo $q;

			$this->zip->add_data("F5T1.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T2_ACTED WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);

			$q =  $q ."\n". $this->generateSQL("F5T2_ACTED",$result);
			echo $q;
			#echo $result;
			$this->zip->add_data("F5T2_ACTED.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T2_NOTACTED WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");

			
			$result =  $this->dbutil->csv_from_result($query);

			$q =  $q ."\n". $this->generateSQL("F5T2_ACTED",$result);

			
			#echo $result;
			$this->zip->add_data("F5T2_NOTACTED.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);

			$q =  $q ."\n". $this->generateSQL("F5T2_NOTACTED",$result);

			#echo $result;
			$this->zip->add_data("F5T2_RCV.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T3 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			#echo $result;
			$q =  $q ."\n". $this->generateSQL("F5T2_RCV",$result);

			$this->zip->add_data("F5T3.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T4 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);

			$q =  $q ."\n". $this->generateSQL("F5T4",$result);
			#echo $result;
			$this->zip->add_data("F5T4.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T5 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			#echo $result;

			$q =  $q ."\n". $this->generateSQL("F5T5",$result);
			$this->zip->add_data("F5T5.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T6_CMPLTD WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);

			$q =  $q ."\n". $this->generateSQL("F5T6_CMPLTD",$result);
			#echo $result;
			$this->zip->add_data("F5T6_CMPLTD.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T7 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			$q =  $q ."\n". $this->generateSQL("F5T7",$result);
			#echo $result;
			$this->zip->add_data("F5T7.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T8 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			$q =  $q ."\n". $this->generateSQL("F5T8",$result);
			#echo $result;
			$this->zip->add_data("F5T8.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T9 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			$q =  $q ."\n". $this->generateSQL("F5T9",$result);
			#echo $result;
			$this->zip->add_data("F5T9.csv", $result);

			$query = $this->db->query("SELECT * FROM F5T10 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			#echo $result;
			$this->zip->add_data("F5T10.csv", $result);
			$q =  $q ."\n". $this->generateSQL("F5T10",$result);

			$query = $this->db->query("SELECT * FROM F5T11 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			#echo $result;
			$this->zip->add_data("F5T11.csv", $result);
			$q =  $q ."\n". $this->generateSQL("F5T11",$result);
			$query = $this->db->query("SELECT * FROM F5T12 WHERE Y_M  >= '".$start."' and Y_M <= '".$end."'");
			
			$result =  $this->dbutil->csv_from_result($query);
			$this->zip->add_data("F5T12.csv", $result);
			$q =  $q ."\n". $this->generateSQL("F5T12",$result);
			#echo $result;
			#$this->zip->download("backup.zip");


			$this->load->helper('download');
			force_download($fileName, $q);
			/*ini_set('memory_limit', '-1');
		    // Load the DB utility class
		    $this->load->dbutil();

		    // Backup your entire database and assign it to a variable
		    $backup =& $this->dbutil->backup();

		    // Load the file helper and write the file to your server
		    $this->load->helper('file');
		    write_file(FCPATH.'/downloads/'.$fileName, $backup);

		    // Load the download helper and send the file to your desktop
		    $this->load->helper('download');
		    force_download($fileName, $backup);*/
		}
	

		public function getDatetime($payload){
			header('Content-Type: application/json');
			if(isset($payload->basehourly)){
				$d = date("Y-m-d H:");
				#$d = substr($d,0,-1);
				$d .= "00";
				$data = array(
							"date" => $d
							);
			}else if(isset($payload->nexthourly)){
				$d = date("Y-m-d H", strtotime("+1 hours"));

				$d .= ":00";
				$data = array(
							"date" => $d
							);
			}else if(isset($payload->nexthour)){
				$d = date("H", strtotime("+1 hours"));

				$d .= ":00";
				$data = array(
							"date" => $d
							);
			}else{
				$d = date("Y-m-d H:i", strtotime('-4 minutes'));
				$d = substr($d,0,-1);
				$d .= "0";
				$data = array(
							"date" => $d
							);
			}
			
			
			$response = array('status' => 'SUCCESS',
				 'message' => 'Retrieving Station Success', 
				 'payload' =>	$data,
				 'request' => $payload);
			return json_encode($response);
	
			
		}

		
		



		public function authenticate($payload){
			header('Content-Type: application/json');

			header('Access-Control-Allow-Origin: *');
	    	header("Access-Control-Allow-Methods: GET, POST");

	    	$referer = "";
	    	if(isset($_SERVER['HTTP_REFERER'])){
	    		$referer = parse_url($_SERVER['HTTP_REFERER']);
				$referer = $referer['host'];
	    	}
	    	
	    	if($referer !== 'uams.probation.gov.ph' && $referer !== '192.168.1.183' && $referer !== '192.168.1.147' && $referer !== '192.168.1.36' && $referer !== '192.168.1.33'  && $referer !== '192.168.1.38'  && $referer !== '192.168.1.35' && $referer !== '192.168.100.3' && $referer !== '192.168.100.14' && $referer !== '192.168.100.14' && $referer !== '192.168.10.199' && $referer !== '192.168.100.4' && $referer !== '192.168.254.115' && $referer !== '192.168.1.219' && $referer !== '127.0.0.1' && $referer !== 'ks' && $referer !== '202.90.136.122' && $referer !== '192.168.0.109'){
			    die('Unauthorized access');
			}
	    	

			if(!isset($payload->USERNAME) && !isset($payload->PASSWORD)){
				$response = array('status' => 'FAILED',
									  'message' => 'INVALID PARAMATERS');
					return json_encode($response);
			}

			$this->db->where(array('USER_EMAIL'=>$payload->USERNAME,'USER_PASS'=>base64_encode($payload->PASSWORD) ));
		    $query = $this->db->get('USERS');

			if($query){
				if($query->num_rows() > 0){
					$user = $query->row();
						$response = array('status' => 'SUCCESS',
										 'message' => 'LOGIN SUCCESS',
										 'payload' => array("USER_ID"=>$user->USER_ID,
										 					"USER_LEVEL_ID" => $user->USER_LEVEL_ID,
										 					"USER_STATUS" => $user->USER_STATUS,
										 					"USER_NAME" => $user->USER_NAME,
										 					"USER_EMAIL" => $user->USER_EMAIL,
										 					"USER_FULLNAME" => $user->USER_FNAME." ".$user->USER_LNAME,
										 					"FIELD_OFFICE" => $user->FIELD_OFFICE,
										 					"USER_CONTACT" => $user->USER_CONTACT,
										 					"HASH" => base64_encode($user->USER_EMAIL).".".base64_encode($payload->PASSWORD)

										 				)
										 		
										 );

						$p2 = (object)array( "created_by" => $user->USER_ID,
											"action" => "LOGGED IN",
											"module" => "AUTHENTICATION" );
						$this->Cmis_Feedback_model->AuditInsert($p2);


						return json_encode($response);
				}else{
						$response = array('status' => 'FAILED',
										  'message' => 'USERNAME or PASSWORD Didn\'t Match!');
						return json_encode($response);
				}

				
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR LOGGING-IN');
					return json_encode($response);
			}	
		}


		public function addIS($payload){
			header('Content-Type: application/json');

			$datenow = date("Y-m-d H:i:s");
			$data = array(	"INFO_SYSTEM_NAME" => $payload->INFO_SYSTEM_NAME,
							"INFO_SYSTEM_STATUS" => $payload->INFO_SYSTEM_STATUS,
							"INFO_SYSTEM_URL" => $payload->INFO_SYSTEM_URL,
							"INFO_SYSTEM_LOGIN_URL" => $payload->INFO_SYSTEM_LOGIN_URL,
							"INFO_SYSTEM_USER_URL" => $payload->INFO_SYSTEM_USER_URL,
							);
			

			if($this->db->insert('INFO_SYSTEM', $data)){
				$response = array('status' => 'SUCCESS',
								'message' => 'IS ADDED SUCCESSFULLY',
								'USER_ID' =>  $this->db->insert_id()
								 );

				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
								  'message' => 'FAILED ADDING IS');
				return json_encode($response);
			}
		}

		public function getISByID($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM INFO_SYSTEM WHERE INFO_SYSTEM_ID = '".$USER_ID."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Level Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Level');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}



		public function doLogout($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
			
			$url = 'http://192.168.1.224/ppa-api/wsv1/Api/doLogout';
			$ch = curl_init($url);
			$data = array(
			    'USER_EMAIL' => $payload->USER_EMAIL
			);
			$payload = json_encode($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);

		}

		public function updateISbyID($payload){
			header('Content-Type: application/json');
			
			$update = array();
			if(isset($payload->INFO_SYSTEM_NAME) && ($payload->INFO_SYSTEM_NAME != "")){
				$update = array_merge($update,  array("INFO_SYSTEM_NAME"=>$payload->INFO_SYSTEM_NAME));
			}
			if(isset($payload->INFO_SYSTEM_STATUS) && ($payload->INFO_SYSTEM_STATUS != "")){
				$update = array_merge($update,  array("INFO_SYSTEM_STATUS"=>$payload->INFO_SYSTEM_STATUS));
			}
			if(isset($payload->INFO_SYSTEM_URL) && ($payload->INFO_SYSTEM_URL != "")){
				$update = array_merge($update,  array("INFO_SYSTEM_URL"=>$payload->INFO_SYSTEM_URL));
			}
			if(isset($payload->INFO_SYSTEM_LOGIN_URL) && ($payload->INFO_SYSTEM_LOGIN_URL != "")){
				$update = array_merge($update,  array("INFO_SYSTEM_LOGIN_URL"=>$payload->INFO_SYSTEM_LOGIN_URL));
			}
			if(isset($payload->INFO_SYSTEM_USER_URL) && ($payload->INFO_SYSTEM_USER_URL != "")){
				$update = array_merge($update,  array("INFO_SYSTEM_USER_URL"=>$payload->INFO_SYSTEM_USER_URL));
			}

			$this->db->reconnect();
			$this->db->where('INFO_SYSTEM_ID',$payload->INFO_SYSTEM_ID);
			if($this->db->update('INFO_SYSTEM', $update)){
				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING INFO SYSTEM'
							 );
				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED UPDATING INFO SYSTEM');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}

		public function getIS($payload){
			header('Content-Type: application/json');
		    $query = $this->db->query("SELECT * FROM INFO_SYSTEM");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getIS Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving getIS List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getIS_Users($payload){
			header('Content-Type: application/json');
		    $query = $this->db->query("SELECT * FROM INFO_SYSTEM");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();

					for($i = 0; $i < sizeof($data); $i++){
						$this->db->reconnect();
						$query2 = $this->db->query("SELECT 1 FROM INFO_SYSTEM_USERS where INFO_SYSTEM_ID = '".$data[$i]->INFO_SYSTEM_ID."' and STATUS = 1");


						$data[$i]->USERS = $query2->num_rows();
					}
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getIS Success', 
							 'payload' =>	$data);
				
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving getIS List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}



		public function getIS_Users_Status($payload){
			header('Content-Type: application/json');
		    $query = $this->db->query("SELECT USER_ID,USER_FNAME,USER_MNAME,USER_LNAME,USER_EMAIL,FIELD_OFFICE FROM USERS");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();

					for($i = 0; $i < sizeof($data); $i++){
						
						$this->db->reconnect();
						$query2 = $this->db->query("SELECT * FROM INFO_SYSTEM_USERS where USER_ID = '".$data[$i]->USER_ID."'");

						if($query2->num_rows() > 1){
							$data[$i]->IS = $query2->result();
						}
						
					}
						
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getIS_STATUS Success', 
							 'payload' =>	$data);
				
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving getIS_STATUS List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}


		public function getAllUserType($payload){
			header('Content-Type: application/json');

		    $query = $this->db->query("CALL getAllUserType(1)");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Tyle List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Tyle List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getDeletedList($payload){
			header('Content-Type: application/json');
			$table = $payload->table;
		    $query = $this->db->query("SELECT * FROM ".$table." WHERE status = 0");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getDeletedList Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving getDeletedList List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}
		public function getAllForms($payload){
			header('Content-Type: application/json');
		    $query = $this->db->query("SELECT * FROM FORMS");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getAllForms Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving getAllForms List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getFormByPage($payload){
			header('Content-Type: application/json');
			$form_page = "";
			if(isset($payload->form_page)){
				$form_page = $payload->form_page;
			}
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM FORMS WHERE form_page = '".$form_page."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Forms Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Forms');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}


		public function UpdateForm($payload){
			header('Content-Type: application/json');
			
			$update = array();
			if(isset($payload->form_CAPTION) && ($payload->form_CAPTION != "")){
				$update = array_merge($update,  array("form_CAPTION"=>$payload->form_CAPTION));
			}

			$this->db->reconnect();
			$this->db->where('form_ID',$payload->form_ID);
			if($this->db->update('FORMS', $update)){
				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING FORMS'
							 );
				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED FORMS STATION');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}


		public function restoreDeleted($payload){
			header('Content-Type: application/json');
			$table = $payload->table;
		    $query = $this->db->query("UPDATE ".$table." SET status = 1 WHERE id = '".$payload->id."'");

			if($query){
				
				$response = array('status' => 'SUCCESS',
					 'message' => 'UPDATE Success');
					
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getAllUserTypes($payload){
			header('Content-Type: application/json');

		    $query = $this->db->query("SELECT * FROM USER_LEVEL");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Tyle List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Tyle List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getAllUserTypeModules($payload){
			header('Content-Type: application/json');

		    $query = $this->db->query("Select * from USER_LEVEL_MODULES");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Tyle List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Tyle List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getAllUserList($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
		    $query = $this->db->query("CALL getAllUserList('".$USER_ID."')");

			if($query){
				if($query->num_rows() > 1){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getAuditTrail($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
		    $query = $this->db->query("CALL getAuditTrail()");

			if($query){
				if($query->num_rows() >= 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getUserByID($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
		    $query = $this->db->query("CALL getAllUserList('".$USER_ID."')");

			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User List Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User List');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getUserPosition($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM USER_POSITION ");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Position Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Position');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}


		public function getIS_USERS_INFO($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
			if(isset($payload->FOR_ENROLMENT) && ($payload->FOR_ENROLMENT != "")){
				$this->db->where('FOR_ENROLMENT',$payload->FOR_ENROLMENT);				
			}

			if(isset($payload->INFO_SYSTEM_ID) && ($payload->INFO_SYSTEM_ID != "")){
				$this->db->where('INFO_SYSTEM_ID',$payload->INFO_SYSTEM_ID);				
			}
			if(isset($payload->STATUS) && ($payload->STATUS != "")){
				$this->db->where('STATUS',$payload->STATUS);				
			}

			if(isset($payload->IS_UPDATE) && ($payload->IS_UPDATE != "")){
				$this->db->where('IS_UPDATE',$payload->IS_UPDATE);				
			}
			if(isset($payload->ENROLMENT_STATUS) && ($payload->ENROLMENT_STATUS != "")){
				$this->db->where('ENROLMENT_STATUS',$payload->ENROLMENT_STATUS);				
			}

			$this->db->where('status',1);
			

			$query = $this->db->get('INFO_SYSTEM_USERS');
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Position Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Position');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getISUserByID($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
			
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT *, INS.INFO_SYSTEM_ID as `SYSTEM_ID` FROM INFO_SYSTEM INS LEFT JOIN `INFO_SYSTEM_USERS`  ISS on INS.INFO_SYSTEM_ID = ISS.INFO_SYSTEM_ID
		    	and ISS.USER_ID = '".$USER_ID."' 
		    	where INS.INFO_SYSTEM_STATUS = '1'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Systems Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Systems');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getUserTypeByID($payload){
			header('Content-Type: application/json');
			$USER_ID = "";
			if(isset($payload->USER_ID)){
				$USER_ID = $payload->USER_ID;
			}
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Level Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Level');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}
		public function getUserPositionByID($payload){
			header('Content-Type: application/json');
			$USER_POSITION_ID = "";
			if(isset($payload->USER_POSITION_ID)){
				$USER_POSITION_ID = $payload->USER_POSITION_ID;
			}
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM USER_POSITION WHERE USER_POSITION_ID = '".$USER_POSITION_ID."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving User Position Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Position');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}
		public function getFieldOfficeByID($payload){
			header('Content-Type: application/json');
			$ID = "";
			if(isset($payload->ID)){
				$ID = $payload->ID;
			}
			#echo "SELECT * FROM USER_LEVEL WHERE USER_LEVEL_ID = '".$USER_ID."'";
		    $query = $this->db->query("SELECT * FROM field_office WHERE ID = '".$ID."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->row();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving Field Office Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving Field Office');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}

		public function getUserTypeByModulesByID($payload){
			header('Content-Type: application/json');
			$LEVEL_ID = "";
			if(isset($payload->LEVEL_ID)){
				$LEVEL_ID = $payload->LEVEL_ID;
			}
		    $query = $this->db->query("SELECT * FROM USER_LEVEL_RIGHTS WHERE USER_LEVEL_ID = '".$LEVEL_ID."'");
		    #echo $query;
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result();
						$response = array('status' => 'SUCCESS',
							 'message' => 'Retrieving getUserTypeByModulesByID Success', 
							 'payload' =>	$data);
					return json_encode($response);
				}else{
						$response = array('status' => 'ERROR',
							  'message' => 'Fail Retrieving User Level');
					return json_encode($response);
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}
		}




	

		public function AddUser($payload){
			header('Content-Type: application/json');

			$datenow = date("Y-m-d H:i:s");
			$data = array(	"USER_FNAME" => $payload->USER_FNAME,
							
							"USER_LNAME" => $payload->USER_LNAME,
							"USER_NAME" => $payload->USER_NAME,
							"USER_POSITION_ID" => $payload->USER_POSITION_ID,
							"USER_CONTACT" => $payload->USER_CONTACT,
							"USER_EMAIL" => $payload->USER_EMAIL,
							"USER_PASS" =>	base64_encode($payload->USER_PASS),
							"USER_LEVEL_ID" => $payload->USER_LEVEL_ID,
							"FIELD_OFFICE" => $payload->FIELD_OFFICE,
							"FIELD_OFFICE_ID" => $payload->FIELD_OFFICE_ID,
							"USER_EXPIRY" => $payload->USER_EXPIRY,
							"CREATED_DATE" => $datenow,
							"CREATED_BY" => $payload->CREATED_BY,
							"USER_STATUS" => $payload->STATUS
							);


			if(isset($payload->USER_MNAME) && ($payload->USER_MNAME != "")){
				$data = array_merge($data,  array("USER_MNAME"=>$payload->USER_MNAME));
			}

			if($this->db->insert('USERS', $data)){
				$response = array('status' => 'SUCCESS',
								'message' => 'USER ADDED SUCCESSFULLY',
								 'USER_ID' =>  $this->db->insert_id()
								 );
				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
								  'message' => 'FAILED ADDING USER');
				return json_encode($response);
			}
		}

		public function AddUserPosition($payload){
			header('Content-Type: application/json');

			$datenow = date("Y-m-d H:i:s");
			$data = array(	"USER_POSITION_NAME" => $payload->USER_POSITION_NAME,
							
							);
			

			if($this->db->insert('USER_POSITION', $data)){
				$response = array('status' => 'SUCCESS',
								'message' => 'USER ADDED SUCCESSFULLY',
								'USER_ID' =>  $this->db->insert_id()
								 );
				

				#var_dump($insert);


				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
								  'message' => 'FAILED ADDING USER POSITION');
				return json_encode($response);
			}
		}


		public function AddUserType($payload){
			header('Content-Type: application/json');

			$datenow = date("Y-m-d H:i:s");
			$data = array(	"USER_LEVEL_NAME" => $payload->USER_LEVEL_NAME,
							"STATUS" => $payload->USER_STATUS
							);
			

			if($this->db->insert('USER_LEVEL', $data)){
				$response = array('status' => 'SUCCESS',
								'message' => 'USER ADDED SUCCESSFULLY',
								'USER_ID' =>  $this->db->insert_id()
								 );
				$LEVEL_ID = $this->db->insert_id();
				$this->db->reconnect();
				$insert = array();
				foreach ($payload->checkbox as $key => $value) {
					#var_dump($value);

					 array_push($insert,  array("USER_LEVEL_ID"=>$LEVEL_ID,"ACCESS_RIGHTS"=>$value->STATUS,"USER_LEVEL_MODULE_ID"=>$value->USER_LEVEL_MODULE_ID));
					#$insert = array_merge($insert,  ));
				}
				$this->db->insert_batch('USER_LEVEL_RIGHTS', $insert);

				#var_dump($insert);


				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
								  'message' => 'FAILED ADDING USER');
				return json_encode($response);
			}
		}
		public function AddFieldOffice($payload){
			header('Content-Type: application/json');

			$datenow = date("Y-m-d H:i:s");
			$data = array(	"REGION" => $payload->REGION,
							"NAME" => $payload->NAME,
							"ENABLED" => $payload->ENABLED,
							"IS_VIRTUAL" => $payload->IS_VIRTUAL,
							);
			

			if($this->db->insert('field_office', $data)){
				$response = array('status' => 'SUCCESS',
								'message' => 'FIELD OFFICE ADDED SUCCESSFULLY',
								'USER_ID' =>  $this->db->insert_id()
								 );
				$LEVEL_ID = $this->db->insert_id();
				
				#var_dump($insert);


				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
								  'message' => 'FAILED ADDING USER');
				return json_encode($response);
			}
		}

		public function UpdateUserPosition($payload){
			header('Content-Type: application/json');
			
			$update = array();

			if(isset($payload->USER_POSITION_NAME) && ($payload->USER_POSITION_NAME != "")){
				$update = array_merge($update,  array("USER_POSITION_NAME"=>$payload->USER_POSITION_NAME));
			}

			
			$this->db->reconnect();
			$this->db->where('USER_POSITION_ID',$payload->USER_POSITION_ID);
			if($this->db->update('USER_POSITION', $update)){
				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING USER POSITION'
							 );

				

				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED UPDATE USER POSITION');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}



		public function upsertIS_User($payload){
			header('Content-Type: application/json');
			//@TODO
			$USER_ID = $payload->USER_ID;
			$INFO_SYSTEM_ID = $payload->INFO_SYSTEM_ID;
			
			$query = $this->db->query("SELECT * FROM INFO_SYSTEM_USERS WHERE USER_ID = '".$USER_ID."' and INFO_SYSTEM_ID = '".$INFO_SYSTEM_ID."'");

			if($query){
				if($query->num_rows() > 0){
					//for UPDATE
					$previous = $query->row();
					$update = array();

					if(isset($payload->STATUS) && ($payload->STATUS != "")){
						$update = array_merge($update,  array("STATUS"=>$payload->STATUS));

						if(($payload->STATUS == 1)){
							if($previous->ENROLMENT_STATUS == 0){

								$update = array_merge($update,  array("FOR_ENROLMENT"=>1));	
							}
						}
					}
					if(isset($payload->FOR_ENROLMENT) && ($payload->FOR_ENROLMENT != "")){
						$update = array_merge($update,  array("FOR_ENROLMENT"=>$payload->FOR_ENROLMENT));
					}
					if(isset($payload->ENROLMENT_STATUS) && ($payload->ENROLMENT_STATUS != "")){
						$update = array_merge($update,  array("ENROLMENT_STATUS"=>$payload->ENROLMENT_STATUS));
					}

					if(isset($payload->IS_UPDATE) && ($payload->IS_UPDATE != "")){
						$update = array_merge($update,  array("IS_UPDATE"=>$payload->IS_UPDATE));
						
					}else{
						if($previous->ENROLMENT_STATUS == 0){
							$update = array_merge($update,  array("IS_UPDATE"=>"1"));	
						}
					}

					
					$update = array_merge($update,  array("LAST_MODIFIED"=>date("Y-m-d H:i:s")));
					
					$this->db->reconnect();
					$this->db->where('INFO_SYSTEM_ID',$payload->INFO_SYSTEM_ID);
					$this->db->where('USER_ID',$payload->USER_ID);
					if($this->db->update('INFO_SYSTEM_USERS', $update)){
						$response = array('status' => 'SUCCESS',
									 'message' => 'SUCCESSFULLY UPDATING USER SYSTEM'
									 );

						return json_encode($response);
					}else{
						$response = array('status' => 'ERROR',
									  'message' => 'FAILED UPDATE USER SYSTEM');
									  #'error_code' => mysqli_error($this->con));
						return json_encode($response);
					}


				}else{
					//FOR INSERT
					// $FOR_ENROLMENT = 0;
					// $IS_UPDATE = 0;
					// if($payload->STATUS == 1){
					// 	$FOR_ENROLMENT = 1;
					// 	$IS_UPDATE = 1;
					// }
					// $data = array(	
					// 			"FOR_ENROLMENT" => $FOR_ENROLMENT,
					// 			"IS_UPDATE" => $IS_UPDATE,
					// 			"STATUS" => $payload->STATUS,
					// 			"INFO_SYSTEM_ID" => $payload->INFO_SYSTEM_ID,
					// 			"USER_ID" => $payload->USER_ID,
					// 			"DATE_CREATED" => date("Y-m-d H:i:s")
					// 		);
			
					$data = array(
								"IS_UPDATE" => "0",
								"FOR_ENROLMENT" => "0",
								"ENROLMENT_STATUS" => "1",
								"STATUS" => $payload->STATUS,
								"INFO_SYSTEM_ID" => $payload->INFO_SYSTEM_ID,
								"USER_ID" => $payload->USER_ID,
								"DATE_CREATED" => date("Y-m-d H:i:s")
							);

					if($this->db->insert('INFO_SYSTEM_USERS', $data)){
						$response = array('status' => 'SUCCESS',
										'message' => 'FIELD OFFICE ADDED SUCCESSFULLY',
										'USER_ID' =>  $this->db->insert_id());
			

						return json_encode($response);
					}else{
						$response = array('status' => 'ERROR',
										  'message' => 'FAILED ADDING USER');
						return json_encode($response);
					}
						
			
				}
			}else{
					$response = array('status' => 'ERROR',
									  'message' => 'ERROR FETCHING RECORDS');
					return json_encode($response);
			}





			
		}


		public function UpdateFieldOffice($payload){
			header('Content-Type: application/json');
			
			$update = array();

			if(isset($payload->REGION) && ($payload->REGION != "")){
				$update = array_merge($update,  array("REGION"=>$payload->REGION));
			}
			if(isset($payload->NAME) && ($payload->NAME != "")){
				$update = array_merge($update,  array("NAME"=>$payload->NAME));
			}
			if(isset($payload->ENABLED) && ($payload->ENABLED != "")){
				switch ($payload->ENABLED) {
					case '1':
						$update = array_merge($update,  array("ENABLED"=>true));
						break;
					
					default:
						$update = array_merge($update,  array("ENABLED"=>false));
						break;
				}
				
			}
			if(isset($payload->IS_VIRTUAL) && ($payload->IS_VIRTUAL != "")){
				$update = array_merge($update,  array("IS_VIRTUAL"=>$payload->IS_VIRTUAL));
			}
			#var_dump($update);
			
			$this->db->reconnect();
			$this->db->where('ID',$payload->ID);
			if($this->db->update('field_office', $update)){
				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING FIELD OFFICE'
							 );

				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED UPDATE FIELD OFFICE');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}

		public function UpdateUserType($payload){
			header('Content-Type: application/json');
			
			$update = array();
			if(isset($payload->STATUS) && ($payload->STATUS != "")){
				$update = array_merge($update,  array("STATUS"=>$payload->STATUS));
			}
			if(isset($payload->USER_LEVEL_NAME) && ($payload->USER_LEVEL_NAME != "")){
				$update = array_merge($update,  array("USER_LEVEL_NAME"=>$payload->USER_LEVEL_NAME));
			}

			
			$this->db->reconnect();
			$this->db->where('USER_LEVEL_ID',$payload->USER_LEVEL_ID);
			if($this->db->update('USER_LEVEL', $update)){
				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING USER'
							 );

				$this->db->reconnect();
				$this->db->query("DELETE FROM USER_LEVEL_RIGHTS WHERE USER_LEVEL_ID='".$payload->USER_LEVEL_ID."'");
				$this->db->reconnect();
				$insert = array();
				foreach ($payload->checkbox as $key => $value) {
					#var_dump($value);

					 array_push($insert,  array("USER_LEVEL_ID"=>$payload->USER_LEVEL_ID,"ACCESS_RIGHTS"=>$value->STATUS,"USER_LEVEL_MODULE_ID"=>$value->USER_LEVEL_MODULE_ID));
					#$insert = array_merge($insert,  ));
				}
				$this->db->insert_batch('USER_LEVEL_RIGHTS', $insert);



				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED USER STATION');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}

		public function UpdateUser($payload){
			header('Content-Type: application/json');
			
			$update = array();
			$isPasswordUpdate = 0;
			if(isset($payload->USER_STATUS) && ($payload->USER_STATUS != "")){
				$update = array_merge($update,  array("USER_STATUS"=>$payload->USER_STATUS));
			}
			if(isset($payload->USER_ID) && ($payload->USER_ID != "")){
				$update = array_merge($update,  array("USER_ID"=>$payload->USER_ID));
			}

			if(isset($payload->USER_NAME) && ($payload->USER_NAME != "")){
				$update = array_merge($update,  array("USER_NAME"=>$payload->USER_NAME));
			}

			if(isset($payload->USER_FNAME) && ($payload->USER_FNAME != "")){
				$update = array_merge($update,  array("USER_FNAME"=>$payload->USER_FNAME));
			}
			if(isset($payload->USER_MNAME) && ($payload->USER_MNAME != "")){
				$update = array_merge($update,  array("USER_MNAME"=>$payload->USER_MNAME));
			}
			if(isset($payload->USER_LNAME) && ($payload->USER_LNAME != "")){
				$update = array_merge($update,  array("USER_LNAME"=>$payload->USER_LNAME));
			}

			if(isset($payload->USER_CONTACT) && ($payload->USER_CONTACT != "")){
				$update = array_merge($update,  array("USER_CONTACT"=>$payload->USER_CONTACT));
			}

			if(isset($payload->USER_EMAIL) && ($payload->USER_EMAIL != "")){
				$update = array_merge($update,  array("USER_EMAIL"=>$payload->USER_EMAIL));
			}

			if(isset($payload->USER_PASS) && ($payload->USER_PASS != "")){
				$update = array_merge($update,  array("USER_PASS"=>base64_encode($payload->USER_PASS)));
				$update = array_merge($update,  array("PASSWORD_CHANGE"=>1));
				$isPasswordUpdate = 1;
			}

			if(isset($payload->USER_LEVEL_ID) && ($payload->USER_LEVEL_ID != "")){
				$update = array_merge($update,  array("USER_LEVEL_ID"=>$payload->USER_LEVEL_ID));
			}

			if(isset($payload->FIELD_OFFICE) && ($payload->FIELD_OFFICE != "")){
				$update = array_merge($update,  array("FIELD_OFFICE"=>$payload->FIELD_OFFICE));
			}

			if(isset($payload->FIELD_OFFICE_ID) && ($payload->FIELD_OFFICE_ID != "")){
				$update = array_merge($update,  array("FIELD_OFFICE_ID"=>$payload->FIELD_OFFICE_ID));
			}
			if(isset($payload->USER_POSITION_ID) && ($payload->USER_POSITION_ID != "")){
				$update = array_merge($update,  array("USER_POSITION_ID"=>$payload->USER_POSITION_ID));
			}

			$this->db->reconnect();
			$this->db->where('USER_ID',$payload->USER_ID);
			if($this->db->update('USERS', $update)){


				$response = array('status' => 'SUCCESS',
							 'message' => 'SUCCESSFULLY UPDATING USER'
							 );

				// if($isPasswordUpdate == 1){
				// 	$this->db->reconnect();
				// 	$update = array();
				// 	$update = array_merge($update,  array("IS_UPDATE"=>1));
				// 	$this->db->where('USER_ID',$payload->USER_ID);
				// 	$this->db->where('ENROLMENT_STATUS',1);
				// 	$this->db->update('INFO_SYSTEM_USERS', $update);
					
				// }


				return json_encode($response);
			}else{
				$response = array('status' => 'ERROR',
							  'message' => 'FAILED USER STATION');
							  #'error_code' => mysqli_error($this->con));
				return json_encode($response);
			}
		}


		






		public function check_database()
		{
		    
		    ini_set('display_errors', 'On');
		    
		    //  Load the database config file.
		    if(file_exists($file_path = APPPATH.'config/database.php'))
		    {
		        include($file_path);
		    }
		    
		    $config = $db[$active_group];
		    
		    //  Check database connection if using mysqli driver
		    if( $config['dbdriver'] === 'mysqli' )
		    {
		        $mysqli = new mysqli( $config['hostname'] , $config['username'] , $config['password'] , $config['database'] );
		        if( !$mysqli->connect_error )
		        {
		            $mysqli->close();
		            return true;
		        }
		        
		        $mysqli->close();
		    }
		    
		    return false;
		} 



		public function cleanUpByFO(){

			$field_office = $_GET['field'];
			echo "\n\nCleanup to F5 T10 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T11 where field_office = '".$field_office."'");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T10 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";

				/*$curr_date = strtotime(date($Y_M."-01"));
				#echo $curr_date;
				$date_transfer = date("Y-m",strtotime("+1 month",$curr_date));
				echo "\nDeleting date: ".$date_transfer."\n";*/

				//$query_check = $this->db->query("SELECT docket_no FROM F21T14_PARDON WHERE field_office = '".$field_office."' and docket_no ='".$value['docket_no']."' and Y_M = '".$date_transfer."' and status = 1");

				
			}


			echo "\n\n\Cleanup to F5 T3 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T4 where field_office = '".$field_office."'");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T3 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";
				/*$curr_date = strtotime(date($Y_M."-01"));
				#echo $curr_date;
				$date_transfer = date("Y-m",strtotime("+1 month",$curr_date));
				echo "\nDeleting date: ".$date_transfer."\n";*/

				//$query_check = $this->db->query("SELECT docket_no FROM F21T14_PARDON WHERE field_office = '".$field_office."' and docket_no ='".$value['docket_no']."' and Y_M = '".$date_transfer."' and status = 1");

				
			}


			echo "\n\n\Cleanup to F5 T1 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T2_ACTED where field_office = '".$field_office."'");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T1 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";

				
			}


		}



		public function cleanUpDuplicates(){
			$payload = (object)array("USER_LEVEL_ID"=>0,"FIELD_OFFICE"=>0);
			$fieldOffice = json_decode($this->Pis_model->getAllFieldOffices($payload));
			$datenow = date("Y-m-d H:i:s");
			#var_dump($fieldOffice);
			if($fieldOffice->status == "SUCCESS"){
				foreach($fieldOffice->payload as $keyFO => $valFO){
					$field_office = $valFO->NAME;
					$Y_M = $_GET['date'];

					$sql = "DELETE FROM F5T1 
							 WHERE id NOT IN (SELECT * 
	                    FROM (SELECT MIN(n1.id)
	                            FROM F5T1 n1
	                        	WHERE n1.Y_M = '".$Y_M."' and n1.field_office = '".$field_office."'
	                        GROUP BY n1.docket_no, n1.field_office) x)
	                        AND Y_M = '".$Y_M."' and field_office = '".$field_office."'";
	                echo $sql."\n";
	                $this->db->reconnect();
					$query = $this->db->query($sql);

				}
			}
		}

		public function cleanUp(){


			echo "\n\nCleanup to F5 T10 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T11");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T10 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";

				/*$curr_date = strtotime(date($Y_M."-01"));
				#echo $curr_date;
				$date_transfer = date("Y-m",strtotime("+1 month",$curr_date));
				echo "\nDeleting date: ".$date_transfer."\n";*/

				//$query_check = $this->db->query("SELECT docket_no FROM F21T14_PARDON WHERE field_office = '".$field_office."' and docket_no ='".$value['docket_no']."' and Y_M = '".$date_transfer."' and status = 1");
				
			}


			echo "\n\nCleanup to F5 T7 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T9");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T7 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";

				/*$curr_date = strtotime(date($Y_M."-01"));
				#echo $curr_date;
				$date_transfer = date("Y-m",strtotime("+1 month",$curr_date));
				echo "\nDeleting date: ".$date_transfer."\n";*/

				//$query_check = $this->db->query("SELECT docket_no FROM F21T14_PARDON WHERE field_office = '".$field_office."' and docket_no ='".$value['docket_no']."' and Y_M = '".$date_transfer."' and status = 1");
				
			}


			echo "\n\nCleanup to F5 T5 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T6_CMPLTD");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T5 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";
			}


			echo "\n\n\Cleanup to F5 T3 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T4");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T3 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";
				/*$curr_date = strtotime(date($Y_M."-01"));
				#echo $curr_date;
				$date_transfer = date("Y-m",strtotime("+1 month",$curr_date));
				echo "\nDeleting date: ".$date_transfer."\n";*/

				//$query_check = $this->db->query("SELECT docket_no FROM F21T14_PARDON WHERE field_office = '".$field_office."' and docket_no ='".$value['docket_no']."' and Y_M = '".$date_transfer."' and status = 1");

				
			}



			echo "\n\n\Cleanup to F5 T1 Started....\n";
			$query = $this->db->query("SELECT * FROM F5T2_ACTED");

			$array1 = array();
			if($query){
				if($query->num_rows() > 0){
					$data = $query->result_array();
					foreach ($data as $key => $value) {
						array_push($array1, $value);
					}
					
				}
			}
			foreach($array1 as $key => $value){
				$this->db->reconnect();

				//var_dump($array1);
				$Y_M = $value['Y_M'];
				$docket_no = $value['docket_no'];
				$field_office = $value['field_office'];

				$sql = "DELETE FROM F5T1 WHERE docket_no = '".$docket_no."' and Y_M > '".$Y_M."' and field_office = '".$field_office."'";

				echo $sql;
				$this->db->query($sql);
				echo "\n";

				
			}


		}

		public function Email() 
	    {
			$payload = json_decode(file_get_contents('php://input'));

			if ($payload != null) {
	            // $data   = (array) $payload;
	            $message_TO = $payload->message_TO;
	            $message_CONTENT = $payload->message_CONTENT;

	            $email_config = Array(
	                'protocol'  => 'smtp',
	                'smtp_host' => '192.168.1.237',
	                'smtp_port' => '587',
	                'smtp_user' => 'notification@probation.gov.ph',
	                'smtp_from_name' => 'PPA Notification',
	                'smtp_pass' => 'N0tific@tion@PPA2022',
	                'mailtype'  => 'html',
	                'charset' => 'utf-8',
	                'starttls'  => 'true',
	                'newline'   => "\r\n"
	            );

	            $this->load->library('email', $email_config);

	            $this->email->from($email_config['smtp_user'], $email_config['smtp_from_name']);
	            $this->email->to($message_TO);
	            $this->email->cc('');
	            $this->email->bcc('');
	            $this->email->subject('OTP');

	            $this->email->message($message_CONTENT);

	            if($this->email->send()) {

		            $data = array(
		            	'email_TO' 		=> $message_TO,
		            	'email_CONTENT' => $message_CONTENT,
		            );
	            	$this->db->insert('email', $data);

	            	$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => "Success Sending OTP!"
					);
					
	            } else {
					$response = array('status' => 'ERROR',
					  'message' => 'Error Sending OTP.'
					);
	            }
                // echo $this->email->print_debugger();

				return json_encode($response);
	        } else {
				$response = array('status' => 'ERROR',
				  'message' => 'Empty Payload.'
				);
			}
			return json_encode($response);
	    }

	}



?>