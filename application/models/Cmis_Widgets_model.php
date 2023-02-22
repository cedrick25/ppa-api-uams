<?php 
	
class Cmis_Widgets_model extends CI_Model
{


	public function __construct() {
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	parent::__construct();
	}

	

	public function widgets($payload)
	{
		if($payload != null)
		{
			switch ($payload->method) {

				case 'workload':
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getWorkloadHandledProbationInv",$payload2));
							$count += $temp->count;
						}
						$ProbationInv = (object)array("count"=>$count);
					}else{
						$ProbationInv = json_decode($this->callProcedure("getWorkloadHandledProbationInv",$payload));
					}

					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getWorkloadHandledProbationSupv",$payload2));
							$count += $temp->count;
						}
						$ProbationSupv = (object)array("count"=>$count);
					}else{
						$ProbationSupv = json_decode($this->callProcedure("getWorkloadHandledProbationSupv",$payload));
					}
					
					$ProbationTotal = (object)array("count"=> $ProbationInv->count + $ProbationSupv->count);
					
					

					$ParoleInv = (object)array("count"=>0);
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getWorkloadHandledParoleSupv",$payload2));
							$count += $temp->count;
						}
						$ParoleSupv = (object)array("count"=>$count);
					}else{
						$ParoleSupv = json_decode($this->callProcedure("getWorkloadHandledParoleSupv",$payload));
					}

					$ParoleTotal = (object)array("count"=> $ParoleSupv->count+$ParoleInv->count);



					$PardonInv = (object)array("count"=>0);
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getWorkloadHandledPardonSupv",$payload2));
							$count += $temp->count;
						}
						$PardonSupv = (object)array("count"=>$count);
					}else{
						$PardonSupv = json_decode($this->callProcedure("getWorkloadHandledPardonSupv",$payload));
					}


					$PardonTotal = (object)array("count"=> $PardonSupv->count+$PardonInv->count);

					$TotalInv = (object)array("count"=>$ProbationInv->count+$ParoleInv->count+$PardonInv->count);
					$totalSupv = (object)array("count"=> $ProbationSupv->count + $PardonSupv->count +$ParoleSupv->count );
					$total = (object)array("count"=> ($ParoleTotal->count+$ProbationTotal->count+$PardonTotal->count)  );

					$payload = array(

								"ProbationInv"=>$ProbationInv,
								"ProbationSupv"=>$ProbationSupv,
								"ProbationTotal"=>$ProbationTotal,
								"ParoleInv"=>$ParoleInv,
								"ParoleSupv"=>$ParoleSupv,
								"ParoleTotal"=>$ParoleTotal,
								"PardonInv"=>$PardonInv,
								"PardonSupv"=>$PardonSupv,
								"PardonTotal"=>$PardonTotal,
								"TotalInv"=>$TotalInv,
								"totalSupv"=>$totalSupv,
								"total"=>$total
								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);

					break;

				case 'new_referral':
					

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp_ProbationInv = json_decode($this->callProcedure("getReferralsReceivedProbationInv",$payload2));

							$count += $temp_ProbationInv->count;
						}
						$ProbationInv = (object)array("count"=>$count);
					}else{
						$ProbationInv = json_decode($this->callProcedure("getReferralsReceivedProbationInv",$payload));
					}

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp_ProbationSupv = json_decode($this->callProcedure("getReferralsReceivedProbationSupv",$payload2));
							$count += $temp_ProbationSupv->count;
						}
						$ProbationSupv = (object)array("count"=>$count);
					}else{
						$ProbationSupv = json_decode($this->callProcedure("getReferralsReceivedProbationSupv",$payload));
					}


					
					$ProbationTotal = (object)array("count"=> $ProbationInv->count + $ProbationSupv->count);
					
					$ParoleInv = (object)array("count"=>0);
					

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getReferralsReceivedParolSupv",$payload2));
							$count += $temp->count;
						}
						$ParoleSupv = (object)array("count"=>$count);
					}else{
						$ParoleSupv = json_decode($this->callProcedure("getReferralsReceivedParolSupv",$payload));
					}

					





					$ParoleTotal = (object)array("count"=> $ParoleSupv->count+$ParoleInv->count);

					$PardonInv = (object)array("count"=>0);
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getReferralsReceivedPardonSupv",$payload2));
							$count += $temp->count;
						}
						$PardonSupv = (object)array("count"=>$count);
					}else{
						$PardonSupv = json_decode($this->callProcedure("getReferralsReceivedPardonSupv",$payload));
					}
					
					




					$PardonTotal = (object)array("count"=> $PardonSupv->count+$PardonInv->count);

					$TotalInv = (object)array("count"=>$ProbationInv->count+$ParoleInv->count+$PardonInv->count);
					$totalSupv = (object)array("count"=> $ProbationSupv->count + $PardonSupv->count +$ParoleSupv->count );
					$total = (object)array("count"=> ($ParoleTotal->count+$ProbationTotal->count+$PardonTotal->count)  );

					$payload = array(

								"ProbationInv"=>$ProbationInv,
								"ProbationSupv"=>$ProbationSupv,
								"ProbationTotal"=>$ProbationTotal,
								"ParoleInv"=>$ParoleInv,
								"ParoleSupv"=>$ParoleSupv,
								"ParoleTotal"=>$ParoleTotal,
								"PardonInv"=>$PardonInv,
								"PardonSupv"=>$PardonSupv,
								"PardonTotal"=>$PardonTotal,
								"TotalInv"=>$TotalInv,
								"totalSupv"=>$totalSupv,
								"total"=>$total
								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);

					break;

				case 'completed_inv_cases':



					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getReferralsReceivedProbationInv",$payload2));
							$count += $temp->count;
						}
						$ProbationPSIR = (object)array("count"=>$count);
					}else{
						$ProbationPSIR = json_decode($this->callProcedure("getReferralsReceivedProbationInv",$payload));
					}

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$temp = json_decode($this->callProcedure("getCompletedInvestigationProbationManifest",$payload2));
							$count += $temp->count;
						}
						$ProbationManifest = (object)array("count"=>$count);
					}else{
						$ProbationManifest = json_decode($this->callProcedure("getCompletedInvestigationProbationManifest",$payload));
					}
					
					$ProbationTotal = (object)array("count"=> $ProbationPSIR->count + $ProbationManifest->count);


					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Parole";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreParole = (object)array("count"=>$count);
					}else{
						
						$PreParole = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}


					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Parole";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreParole = (object)array("count"=>$count);
					}else{
						$payload->filter = "Parole";
						$PreParole = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}


					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Commutation";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreCommutation = (object)array("count"=>$count);
					}else{
						$payload->filter = "Commutation";
						$PreCommutation = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Conditional";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreConditional = (object)array("count"=>$count);
					}else{
						$payload->filter = "Conditional";
						$PreConditional = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Absolute";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreAbsolute = (object)array("count"=>$count);
					}else{
						$payload->filter = "Absolute";
						$PreAbsolute = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Other";
							$temp = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload2));
							$count += $temp->count;
						}
						$PreOther = (object)array("count"=>$count);
					}else{
						$payload->filter = "Other";
						$PreOther = json_decode($this->callProcedure2("getCompletedInvestigationPreParoleFilter",$payload));
					}
					
					
					
					

					$preTotal = (object)array("count"=> ($PreParole->count + $PreCommutation->count + $PreConditional->count + $PreAbsolute->count + $PreOther->count));

					
					//$preProbation = 
					
					$payload = array(
								"ProbationPSIR"=>$ProbationPSIR,
								"ProbationManifest"=>$ProbationManifest,
								"ProbationTotal"=>$ProbationTotal,
								"PreParole"=>$PreParole,
								"PreCommutation"=>$PreCommutation,
								"PreConditional"=>$PreConditional,
								"PreAbsolute"=>$PreAbsolute,
								"PreOther"=>$PreOther,
								"preTotal"=>$preTotal,

								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);

					break;


				case 'completed_supv_cases':
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Term";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationTerm1 = (object)array("count"=>$count);
					}else{
						$payload->filter = "Term";
						$ProbationTerm1 = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload));
					}


					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Revoc";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationRevoc = (object)array("count"=>$count);
					}else{
						$payload->filter = "Revoc";
						$ProbationRevoc = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload));
					}
					

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Termination - Died";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationDied = (object)array("count"=>$count);
					}else{
						$payload->filter = "Termination - Died";
						$ProbationDied = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload));
					}
					

					$ProbationTerm = (object)array("count"=> ($ProbationTerm1->count - $ProbationDied->count));


					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Other";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationOther = (object)array("count"=>$count);
					}else{
						$payload->filter = "Other";
						$ProbationOther = json_decode($this->callProcedure2("getCompletedSupervisionProbationFilter",$payload));
					}
					
					$ProbationTotal = (object)array("count"=> ($ProbationTerm1->count + $ProbationRevoc->count + $ProbationOther->count ));


					######

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "FINAL";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload2));
							$count += $temp->count;
						}
						$ParoleFinal = (object)array("count"=>$count);
					}else{
						$payload->filter = "FINAL";
						$ParoleFinal = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload));
					}

					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "DEATH";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload2));
							$count += $temp->count;
						}
						$ParoleDeath = (object)array("count"=>$count);
					}else{
						$payload->filter = "DEATH";
						$ParoleDeath = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload));
					}

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "OTHER";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload2));
							$count += $temp->count;
						}
						$ParoleOther = (object)array("count"=>$count);
					}else{
						$payload->filter = "OTHER";
						$ParoleOther = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "ARREST";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload2));
							$count += $temp->count;
						}
						$ParoleArrest = (object)array("count"=>$count);
					}else{
						$payload->filter = "ARREST";
						$ParoleArrest = json_decode($this->callProcedure2("getCompletedSupervisionParoleFilter",$payload));
					}
					
					$ParoleTotal = (object)array("count"=> ($ParoleFinal->count + $ParoleDeath->count + $ParoleOther->count + $ParoleArrest->count  ));

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "FINAL";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload2));
							$count += $temp->count;
						}
						$PardonFinal = (object)array("count"=>$count);
					}else{
						$payload->filter = "FINAL";
						$PardonFinal = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "DEATH";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload2));
							$count += $temp->count;
						}
						$PardonDeath = (object)array("count"=>$count);
					}else{
						$payload->filter = "DEATH";
						$PardonDeath = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload));
					}
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "OTHER";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload2));
							$count += $temp->count;
						}
						$PardonOther = (object)array("count"=>$count);
					}else{
						$payload->filter = "OTHER";
						$PardonOther = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "ARREST";
							$temp = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload2));
							$count += $temp->count;
						}
						$PardonArrest = (object)array("count"=>$count);
					}else{
						$payload->filter = "ARREST";
						$PardonArrest = json_decode($this->callProcedure2("getCompletedSupervisionPardonFilter",$payload));
					}

					$PardonTotal = (object)array("count"=> ($PardonFinal->count + $PardonDeath->count + $PardonOther->count + $PardonArrest->count  ));

					//$preProbation = 
					
					$payload = array(
								"ProbationTerm"=>$ProbationTerm,
								"ProbationRevoc"=>$ProbationRevoc,
								"ProbationOther"=>$ProbationOther,
								"ProbationDied"=>$ProbationDied,
								"ProbationTotal"=>$ProbationTotal,

								"ParoleFinal"=>$ParoleFinal,
								"ParoleDeath"=>$ParoleDeath,
								"ParoleOther"=>$ParoleOther,
								"ParoleArrest"=>$ParoleArrest,
								"ParoleTotal"=>$ParoleTotal,

								"PardonFinal"=>$PardonFinal,
								"PardonDeath"=>$PardonDeath,
								"PardonOther"=>$PardonOther,
								"PardonArrest"=>$PardonArrest,
								"PardonTotal"=>$PardonTotal,

								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);

					break;

				case 'court_disposition':
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Granted";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationGranted = (object)array("count"=>$count);
					}else{
						$payload->filter = "Granted";
						$ProbationGranted = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Denied";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationDenied = (object)array("count"=>$count);
					}else{
						$payload->filter = "Denied";
						$ProbationDenied = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Withdrawal";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationWithdrawal = (object)array("count"=>$count);
					}else{
						$payload->filter = "Withdrawal";
						$ProbationWithdrawal = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Dismissed";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$ProbationDismissed = (object)array("count"=>$count);
					}else{
						$payload->filter = "Dismissed";
						$ProbationDismissed = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Reinvestigation";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$Reinvestigation = (object)array("count"=>$count);
					}else{
						$payload->filter = "Reinvestigation";
						$Reinvestigation = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Recall";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$Recall = (object)array("count"=>$count);
					}else{
						$payload->filter = "Recall";
						$Recall = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$payload2->field_office = $val->NAME;
							$payload2->filter = "Warrant";
							$temp = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload2));
							$count += $temp->count;
						}
						$Warrant = (object)array("count"=>$count);
					}else{
						$payload->filter = "Warrant";
						$Warrant = json_decode($this->callProcedure2("getCourtDispositionProbationFilter",$payload));
					}
					


					

					$ProbationDisqualified = (object)array("count"=>0);
					$ProbationOther = (object)array("count"=> ($Reinvestigation->count + $Recall->count + $ProbationDisqualified->count ));
					$ProbationTotal = (object)array("count"=> ($ProbationOther->count + $ProbationGranted->count + $ProbationDenied->count + $ProbationWithdrawal->count  + $ProbationDismissed->count));

				
					//$preProbation = 
					
					$payload = array(
								"ProbationGranted"=>$ProbationGranted,
								"ProbationDenied"=>$ProbationDenied,
								"ProbationWithdrawal"=>$ProbationWithdrawal,
								"ProbationDismissed"=>$ProbationDismissed,
								"ProbationDisqualified"=>$ProbationDisqualified,
								"ProbationTotal"=>$ProbationTotal,
								"ProbationOther"=>$ProbationOther,

								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);

					break;

				case 'plea_bargain':
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$payload2 = (object)(array)$payload;
							$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$val->NAME."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1");
							$count += $query->num_rows();
						}
						$InvestigationCases = $count;
					}else{
						$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$payload->field_office."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1");
						$InvestigationCases = $query->num_rows();
					}
					$this->db->reconnect();

					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$this->db->reconnect();
							$payload2 = (object)(array)$payload;
							$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$val->NAME."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1 and plea_bargain ='YES'");
							$count += $query->num_rows();
						}
						$DrugRelated = $count;
					}else{
						$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$payload->field_office."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1 and plea_bargain ='YES'");
						$DrugRelated = $query->num_rows();
					}
					$this->db->reconnect();
					if(strpos($payload->field_office,'Region') !== false ){
						$payload_R = (object)array("REGION"=>$payload->region_id);
						$fieldOffices = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload_R));
						$count = 0;
						foreach($fieldOffices->payload as $key => $val){
							$this->db->reconnect();
							$payload2 = (object)(array)$payload;
							$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$val->NAME."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1 and plea_bargain !='YES'");
							$count += $query->num_rows();
						}
						$NonDrugRelated = $count;
					}else{
						$query = $this->db->query("SELECT * FROM F5T2_RCV WHERE field_office = '".$payload->field_office."' and Y_M  >= '".$payload->start_date."' and Y_M <= '".$payload->end_date."' and status = 1 and plea_bargain !='YES'");
						$NonDrugRelated= $query->num_rows();
					}

					$NonPlea = $InvestigationCases - ($DrugRelated + $NonDrugRelated);
					$payload = array(
								"InvestigationCases"=>$InvestigationCases,
								"DrugRelated"=>$DrugRelated,
								"NonDrugRelated"=>$NonDrugRelated,
								"NonPlea"=>$NonPlea,

								);
					

					$response = array(
						'status' => 'SUCCESS',
						'message' => 'SUCCESS',
						'payload' => $payload
					);
					break;

				default :
					$response = array(
						'status' => 'ERROR',
						'message' => 'ERROR'
					);
					break;
				}
		}else{
			$response = array(
				'status' => 'ERROR',
				'message' => 'ERROR'
			);
		}
		return json_encode($response);
		
	}
	//FUNCTIONS

	public function callProcedure($procedure,$payload){
		
		$field_office = "";
		$start_date = "";
		$end_date = "";
		if(isset($payload->field_office)){
			$field_office = $payload->field_office;
		}
		if(isset($payload->start_date)){
			$start_date = $payload->start_date;
		}
		if(isset($payload->end_date)){
			$end_date = $payload->end_date;
		}
	    $query = $this->db->query("CALL ".$procedure."('".$start_date."','".$end_date."','".$field_office."')");

		if($query){
			if($query->num_rows() > 0){
				$data = $query->row();
					$response = $data;
					$query->next_result(); 
					$query->free_result(); 
				return json_encode($response);
			}else{
					$response = array('status' => 'ERROR',
						  'message' => 'Fail Retrieving Data');
				return json_encode($response);
		
			}
		}else{
				$response = array('status' => 'ERROR',
								  'message' => 'ERROR FETCHING RECORDS',
								  'error_code' => mysqli_error($this->con));
				return json_encode($response);
		}
	}

	public function callProcedure2($procedure,$payload){
		
		$field_office = "";
		$start_date = "";
		$end_date = "";
		if(isset($payload->field_office)){
			$field_office = $payload->field_office;
		}
		if(isset($payload->start_date)){
			$start_date = $payload->start_date;
		}
		if(isset($payload->end_date)){
			$end_date = $payload->end_date;
		}
		if(isset($payload->filter)){
			$filter = $payload->filter;
		}
	    $query = $this->db->query("CALL ".$procedure."('".$start_date."','".$end_date."','".$field_office."','".$filter."')");

		if($query){
			if($query->num_rows() > 0){
				$data = $query->row();
					$response = $data;
					$query->next_result(); 
					$query->free_result(); 
				return json_encode($response);
			}else{
					$response = array('status' => 'ERROR',
						  'message' => 'Fail Retrieving Data');
				return json_encode($response);
		
			}
		}else{
				$response = array('status' => 'ERROR',
								  'message' => 'ERROR FETCHING RECORDS',
								  'error_code' => mysqli_error($this->con));
				return json_encode($response);
		}
	}


}