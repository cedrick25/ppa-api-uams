<?php

class Webauthn_rp {

	public function newChallenge(){
		return $this->b64url(openssl_random_pseudo_bytes(32));
	}

	public function verifyRegistration($clientDataB64, $attestationB64, $challenge, $origin, $rpId){
		$clientDataJson = $this->b64urlDecode($clientDataB64);
		$attestation = $this->b64urlDecode($attestationB64);
		$this->assertClientData($clientDataJson, 'webauthn.create', $challenge, $origin);

		$offset = 0;
		$decoded = Webauthn_cbor::decode($attestation, $offset);
		if(!is_array($decoded) || !isset($decoded['authData'])){
			throw new Exception('attestation');
		}
		$auth = $this->parseAuthData($decoded['authData'], true);
		if(!hash_equals(hash('sha256', $rpId, true), $auth['rpIdHash'])){
			throw new Exception('rpId');
		}
		if(($auth['flags'] & 0x01) !== 0x01 || ($auth['flags'] & 0x04) !== 0x04){
			throw new Exception('user verification');
		}
		$key = $this->coseToPublicKey($auth['cred']['cose']);
		return array(
			'credentialId' => $this->b64url($auth['cred']['id']),
			'publicKey' => $key['pem'],
			'alg' => $key['alg'],
			'signCount' => $auth['signCount']
		);
	}

	public function verifyAssertion($clientDataB64, $authDataB64, $signatureB64, $challenge, $origin, $rpId, $publicKeyPem, $alg, $storedSignCount){
		$clientDataJson = $this->b64urlDecode($clientDataB64);
		$authData = $this->b64urlDecode($authDataB64);
		$signature = $this->b64urlDecode($signatureB64);
		$this->assertClientData($clientDataJson, 'webauthn.get', $challenge, $origin);

		$auth = $this->parseAuthData($authData, false);
		if(!hash_equals(hash('sha256', $rpId, true), $auth['rpIdHash'])){
			throw new Exception('rpId');
		}
		if(($auth['flags'] & 0x01) !== 0x01 || ($auth['flags'] & 0x04) !== 0x04){
			throw new Exception('user verification');
		}

		$signed = $authData.hash('sha256', $clientDataJson, true);
		$verified = $this->verifySignature($signed, $signature, $publicKeyPem, $alg);
		if($verified !== true){
			throw new Exception('signature');
		}
		if($storedSignCount > 0 && $auth['signCount'] > 0 && $auth['signCount'] < $storedSignCount){
			throw new Exception('signCount');
		}
		if($auth['signCount'] > $storedSignCount){
			return $auth['signCount'];
		}
		return $storedSignCount;
	}

	private function assertClientData($clientDataJson, $type, $challenge, $origin){
		$client = json_decode($clientDataJson, true);
		if(!is_array($client) || !isset($client['type']) || !isset($client['challenge']) || !isset($client['origin'])){
			throw new Exception('clientData');
		}
		if($client['type'] !== $type){
			throw new Exception('type');
		}
		if(!hash_equals($challenge, $client['challenge'])){
			throw new Exception('challenge');
		}
		if($client['origin'] !== $origin){
			throw new Exception('origin');
		}
		if(isset($client['crossOrigin']) && $client['crossOrigin'] === true){
			throw new Exception('crossOrigin');
		}
	}

	private function parseAuthData($authData, $requireCredential){
		if(strlen($authData) < 37){
			throw new Exception('authData');
		}
		$rpIdHash = substr($authData, 0, 32);
		$flags = ord($authData[32]);
		$signCount = unpack('N', substr($authData, 33, 4));
		$cred = null;
		if(($flags & 0x40) === 0x40){
			if(strlen($authData) < 55){
				throw new Exception('attested');
			}
			$credLen = unpack('n', substr($authData, 53, 2));
			$credLen = $credLen[1];
			$credId = substr($authData, 55, $credLen);
			$offset = 55 + $credLen;
			$cose = Webauthn_cbor::decode($authData, $offset);
			$cred = array('id' => $credId, 'cose' => $cose);
		} elseif($requireCredential){
			throw new Exception('credential');
		}
		return array(
			'rpIdHash' => $rpIdHash,
			'flags' => $flags,
			'signCount' => $signCount[1],
			'cred' => $cred
		);
	}

	private function coseToPublicKey($cose){
		if(!is_array($cose) || !isset($cose[1])){
			throw new Exception('cose');
		}
		$kty = $cose[1];
		$alg = isset($cose[3]) ? $cose[3] : null;
		if($kty === 2){
			if(!isset($cose[-2]) || !isset($cose[-3])){
				throw new Exception('cose-ec');
			}
			$x = str_pad(substr($cose[-2], -32), 32, "\x00", STR_PAD_LEFT);
			$y = str_pad(substr($cose[-3], -32), 32, "\x00", STR_PAD_LEFT);
			$point = "\x04".$x.$y;
			$spki = hex2bin('3059301306072a8648ce3d020106082a8648ce3d030107034200').$point;
			return array('pem' => $this->pem($spki), 'alg' => -7);
		}
		if($kty === 3){
			if(!isset($cose[-1]) || !isset($cose[-2])){
				throw new Exception('cose-rsa');
			}
			$n = $this->unsigned($cose[-1]);
			$e = $this->unsigned($cose[-2]);
			$mod = "\x02".$this->derLen(strlen($n)).$n;
			$exp = "\x02".$this->derLen(strlen($e)).$e;
			$seq = "\x30".$this->derLen(strlen($mod.$exp)).$mod.$exp;
			$algId = hex2bin('300d06092a864886f70d0101010500');
			$bit = "\x03".$this->derLen(strlen($seq) + 1)."\x00".$seq;
			$spki = "\x30".$this->derLen(strlen($algId.$bit)).$algId.$bit;
			$useAlg = ($alg === -257 || $alg === null) ? -257 : (int)$alg;
			return array('pem' => $this->pem($spki), 'alg' => $useAlg);
		}
		throw new Exception('cose-kty');
	}

	private function verifySignature($signed, $signature, $publicKeyPem, $alg){
		$key = openssl_pkey_get_public($publicKeyPem);
		if($key === false){
			throw new Exception('public-key');
		}
		$candidates = array($signature);
		if((int)$alg === -7 && strlen($signature) === 64){
			$candidates = array($this->rawEcToDer($signature), $signature);
		}
		foreach($candidates as $candidate){
			$result = openssl_verify($signed, $candidate, $key, OPENSSL_ALGO_SHA256);
			if($result === 1){
				return true;
			}
		}
		return false;
	}

	private function rawEcToDer($signature){
		$r = $this->unsigned(substr($signature, 0, 32));
		$s = $this->unsigned(substr($signature, 32, 32));
		$body = "\x02".$this->derLen(strlen($r)).$r."\x02".$this->derLen(strlen($s)).$s;
		return "\x30".$this->derLen(strlen($body)).$body;
	}

	private function unsigned($bin){
		$bin = ltrim($bin, "\x00");
		if($bin === ''){
			$bin = "\x00";
		}
		if((ord($bin[0]) & 0x80) === 0x80){
			$bin = "\x00".$bin;
		}
		return $bin;
	}

	private function derLen($len){
		if($len < 128){
			return chr($len);
		}
		$bin = ltrim(pack('N', $len), "\x00");
		return chr(0x80 | strlen($bin)).$bin;
	}

	private function pem($der){
		return "-----BEGIN PUBLIC KEY-----\n".chunk_split(base64_encode($der), 64, "\n")."-----END PUBLIC KEY-----\n";
	}

	private function b64url($bin){
		return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
	}

	private function b64urlDecode($value){
		$remainder = strlen($value) % 4;
		if($remainder){
			$value .= str_repeat('=', 4 - $remainder);
		}
		$decoded = base64_decode(strtr($value, '-_', '+/'), true);
		if($decoded === false){
			throw new Exception('base64');
		}
		return $decoded;
	}
}

class Webauthn_cbor {

	public static function decode($data, &$offset){
		$length = strlen($data);
		if($offset >= $length){
			throw new Exception('cbor');
		}
		$initial = ord($data[$offset]);
		$offset++;
		$major = $initial >> 5;
		$additional = $initial & 0x1f;
		$value = self::readLength($data, $offset, $additional);

		if($major === 0){
			return $value;
		}
		if($major === 1){
			return -1 - $value;
		}
		if($major === 2 || $major === 3){
			$slice = substr($data, $offset, $value);
			$offset += $value;
			return $slice;
		}
		if($major === 4){
			$items = array();
			for($i = 0; $i < $value; $i++){
				$items[] = self::decode($data, $offset);
			}
			return $items;
		}
		if($major === 5){
			$map = array();
			for($i = 0; $i < $value; $i++){
				$key = self::decode($data, $offset);
				$map[$key] = self::decode($data, $offset);
			}
			return $map;
		}
		if($major === 6){
			return self::decode($data, $offset);
		}
		if($major === 7 && ($additional === 20 || $additional === 21 || $additional === 22)){
			if($additional === 20){
				return false;
			}
			if($additional === 21){
				return true;
			}
			return null;
		}
		throw new Exception('cbor-type');
	}

	private static function readLength($data, &$offset, $additional){
		if($additional < 24){
			return $additional;
		}
		if($additional === 24){
			$number = ord($data[$offset]);
			$offset += 1;
			return $number;
		}
		if($additional === 25){
			$number = unpack('n', substr($data, $offset, 2));
			$offset += 2;
			return $number[1];
		}
		if($additional === 26){
			$number = unpack('N', substr($data, $offset, 4));
			$offset += 4;
			return $number[1];
		}
		if($additional === 27){
			$high = unpack('N', substr($data, $offset, 4));
			$low = unpack('N', substr($data, $offset + 4, 4));
			$offset += 8;
			if($high[1] > 0){
				throw new Exception('cbor-size');
			}
			return $low[1];
		}
		throw new Exception('cbor-length');
	}
}
