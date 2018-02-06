<?php

/*
 * AffinityOps
 *
 * @author      AffinityOps Developers & Contributors
 * @copyright   Copyright (c) 2018 AffinityOps.com
 * @license     https://affinityops.com/license.txt
 * @link        https://affinityops.com
 */

class payscout 
{

	/**
	* set login credentials
	**/
	public function setLogin($username, $password, $token) {
		$this->login['client_username'] = $username;
		$this->login['client_password'] = $password;
		$this->login['client_token']	= $token;
	}

	/**
	* Set API URL
	**/
	public function setApi($mode) {
		// Mode can be based off of dev (or demo) or live

		if( !empty($mode) ) {
			switch($mode) {
				case 'devVault':
					$this->api['url'] = 'https://gatewaystaging.paymentecommerce.com/api/vault';
				break;
				case 'liveVault':
					$this->api['url'] = 'https://gateway.paymentecommerce.com/api/vault';
				break;
				case 'devProcess':
					$this->api['url'] = 'https://gatewaystaging.paymentecommerce.com/api/process';
				break;
				case 'liveProcess':
					$this->api['url'] = 'https://gateway.paymentecommerce.com/api/process';
				break;
			}
		} else {
			return FALSE;
		}

	}

	public function setBilling(
		$first_name,
		$last_name,
		$email_address,
		$address_line_1,
		$city,
		$postal_code,
		$country,
		$phone_number
			){
		$this->billing['first_name']		= $first_name;
	    $this->billing['last_name']			= $last_name;
	    $this->billing['email_address']		= $email_address;
	    $this->billing['address_line_1']	= $address_line_1;
	    $this->billing['city']				= $city;
	    $this->billing['postal_code']       = $postal_code;
	    $this->billing['country']   		= $country;
	    $this->billing['phone_number']		= $phone_number;
	}

	/**
	* Create Token
	**/
	public function addToken($account_number, $expiration_month, $expiration_year, $customer_reference) {
	
		// Build arrayData
		$arrayData = array(
			"client_username"		=> $this->login['client_username'],
		    "client_password"		=> $this->login['client_password'],
		    "client_token"			=> $this->login['client_token'],
		    "action"				=> 'add',
		    "account_number"		=> $account_number,
		    "expiration_month"		=> $expiration_month,
		    "expiration_year"		=> $expiration_year,
		    "customer_reference"	=> $customer_reference
		);
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Get Token Info
	**/
	public function getTokenInfo($customer_reference) {
	
		// Build arrayData
		$arrayData = array(
			"client_username"		=> $this->login['client_username'],
		    "client_password"		=> $this->login['client_password'],
		    "client_token"			=> $this->login['client_token'],
		    "action"				=> 'get_token_info',
		    "customer_reference"	=> $customer_reference
		);
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Delete Token
	**/
	public function deleteToken($token, $reason) {
	
		// Build arrayData
		$arrayData = array(
			"client_username"		=> $this->login['client_username'],
		    "client_password"		=> $this->login['client_password'],
		    "client_token"			=> $this->login['client_token'],
		    "action"				=> 'delete',
		    "token"					=> $token,
		    "reason"				=> $reason
		);
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Update Token
	**/
	public function updateToken($token, $reason) {
	
		// Build arrayData
		$arrayData = array(
			"client_username"			=> $this->login['client_username'],
		    "client_password"			=> $this->login['client_password'],
		    "client_token"				=> $this->login['client_token'],
		    "action"					=> 'update',
		    "token"						=> $token,
		    "reason"					=> $reason,
		    //any of this fields
		    "billing_first_name"		=> $this->billing['first_name'],
		    "billing_last_name"			=> $this->billing['last_name'],
		    "billing_email_address"		=> $this->billing['email_address'],
		    "billing_address_line_1"	=> $this->billing['address_line_1'],
		    "billing_city"				=> $this->billing['city'],
		    "billing_postal_code"		=> $this->billing['postal_code'],
		    "billing_country"			=> $this->billing['country'],
		    "billing_phone_number"		=> $this->billing['phone_number']
		);
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Sale With Token
	**/
	public function saleToken($token, $amount, $customer_reference) {
		// Build arrayData
		$arrayData = array(
			"client_username"	=> $this->login['client_username'],
		    "client_password"	=> $this->login['client_password'],
		    "client_token"		=> $this->login['client_token'],
		    "processing_type"	=> 'SALE',
		    "token"				=> $token,
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD',
		    "pass_through"		=> '<customerRef>' . $customer_reference . '</customerRef>'
		);
    	
    	return $this->_Post($arrayData);
	}

	public function _Post($postData)
	{
		$cert = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR . 'cacert.pem';

		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => $this->api['url'],
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => "",
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 30,
		    CURLOPT_SSLVERSION => 6,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_CAINFO => $cert,
		    CURLOPT_POSTFIELDS => http_build_query($postData),
		    CURLOPT_HTTPHEADER => array(
		        "cache-control: no-cache",
		        "content-type: application/x-www-form-urlencoded"
		    ),
		));

		$response = curl_exec($curl);
		$this->response = json_decode($response, true);

		$this->err = curl_error($curl);
		curl_close($curl);

		if ($this->err) {
		    return $this->err;
		} else {
		    return $this->response;
		}
	}

}
