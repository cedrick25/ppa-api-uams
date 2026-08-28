<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edms_model extends CI_Model {

	private $user_columns = array(
		'USER_ID',
		'USER_FNAME',
		'USER_MNAME',
		'USER_LNAME',
		'USER_NAME',
		'USER_CONTACT',
		'USER_EMAIL',
		'USER_PASS',
		'USER_LEVEL_ID',
		'FIELD_OFFICE',
		'FIELD_OFFICE_ID',
		'USER_POSITION_ID',
		'USER_STATUS',
		'CREATED_BY',
		'CREATED_DATE',
		'USER_EXPIRY',
		'PASSWORD_CHANGE'
	);

	public function getUsers($payload)
	{
		$this->applyFilters($payload);
		$this->applyPaging($payload);

		$this->db->select($this->user_columns);
		$this->db->from('USERS');
		$this->db->order_by('USER_ID', 'ASC');
		$query = $this->db->get();

		if ($query === FALSE) {
			return json_encode(array(
				'status' => 'ERROR',
				'message' => 'ERROR FETCHING RECORDS'
			));
		}

		$data = $query->result();
		return json_encode(array(
			'status' => 'SUCCESS',
			'message' => 'Retrieving users success',
			'count' => count($data),
			'payload' => $data
		));
	}

	public function getUser($payload)
	{
		if (!is_object($payload) || (
			(!isset($payload->USER_ID) || $payload->USER_ID === '') &&
			(!isset($payload->USER_EMAIL) || $payload->USER_EMAIL === '')
		)) {
			return json_encode(array(
				'status' => 'ERROR',
				'message' => 'USER_ID or USER_EMAIL is required'
			));
		}

		if (isset($payload->USER_ID) && $payload->USER_ID !== '') {
			$this->db->where('USER_ID', $payload->USER_ID);
		} else {
			$this->db->where('USER_EMAIL', $payload->USER_EMAIL);
		}

		$this->db->select($this->user_columns);
		$this->db->from('USERS');
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query === FALSE) {
			return json_encode(array(
				'status' => 'ERROR',
				'message' => 'ERROR FETCHING RECORDS'
			));
		}

		if ($query->num_rows() < 1) {
			return json_encode(array(
				'status' => 'ERROR',
				'message' => 'User not found'
			));
		}

		$user = $query->row();
		return json_encode(array(
			'status' => 'SUCCESS',
			'message' => 'Retrieving users success',
			'count' => 1,
			'payload' => $user
		));
	}

	private function applyFilters($payload)
	{
		if (!is_object($payload)) {
			return;
		}

		if (isset($payload->USER_STATUS) && $payload->USER_STATUS !== '') {
			$this->db->where('USER_STATUS', $payload->USER_STATUS);
		}
		if (isset($payload->USER_EMAIL) && $payload->USER_EMAIL !== '') {
			$this->db->where('USER_EMAIL', $payload->USER_EMAIL);
		}
		if (isset($payload->USER_ID) && $payload->USER_ID !== '') {
			$this->db->where('USER_ID', $payload->USER_ID);
		}
	}

	private function applyPaging($payload)
	{
		if (!is_object($payload) || !isset($payload->limit) || $payload->limit === '') {
			return;
		}

		$limit = (int) $payload->limit;
		if ($limit < 1) {
			return;
		}

		$page = 1;
		if (isset($payload->page) && $payload->page !== '') {
			$page = (int) $payload->page;
			if ($page < 1) {
				$page = 1;
			}
		}

		$this->db->limit($limit, ($page - 1) * $limit);
	}
}
