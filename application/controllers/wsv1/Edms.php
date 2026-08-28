<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edms extends CI_Controller {

	public function __construct() {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Api-Key');

		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			http_response_code(200);
			exit();
		}

		parent::__construct();
		$this->load->model('Edms_model');
		$this->requireApiKey();
	}

	public function getUsers()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Edms_model->getUsers($payload);
	}

	public function getUser()
	{
		$payload = json_decode(file_get_contents('php://input'));
		echo $this->Edms_model->getUser($payload);
	}

	private function requireApiKey()
	{
		$expected = (string) $this->config->item('edms_api_key');
		$provided = $this->getProvidedApiKey();

		if ($expected === '' || $provided === '' || !hash_equals($expected, $provided)) {
			http_response_code(401);
			echo json_encode(array(
				'status' => 'ERROR',
				'message' => 'Unauthorized'
			));
			exit();
		}
	}

	private function getProvidedApiKey()
	{
		$headers = array();
		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
		}
		if (!is_array($headers)) {
			$headers = array();
		}

		foreach ($headers as $name => $value) {
			$lower = strtolower($name);
			if ($lower === 'x-api-key') {
				return trim((string) $value);
			}
			if ($lower === 'authorization' && stripos($value, 'Bearer ') === 0) {
				return trim(substr($value, 7));
			}
		}

		if (!empty($_SERVER['HTTP_X_API_KEY'])) {
			return trim((string) $_SERVER['HTTP_X_API_KEY']);
		}

		$auth = '';
		if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
			$auth = $_SERVER['HTTP_AUTHORIZATION'];
		} elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
			$auth = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		}

		if ($auth !== '' && stripos($auth, 'Bearer ') === 0) {
			return trim(substr($auth, 7));
		}

		return '';
	}
}
