<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| The helper at ppa-uams/tools/futronic-agent must use this same hmac_secret.
| template_key stays on the API and encrypts FS64 templates at rest.
| Replace both values before using this beyond a trusted workstation.
*/
$config['hmac_secret'] = '9f3c1e7a6b284d05c8e17a4f0d6b3952e8c71a0f4d9b26e3';
$config['template_key'] = 'c4a8e1d07b395f26a1e8c4d90f7b3516e2a9d4c08b17f635';
