<?php

/**
 * Payscout Gateway
 *
 * @link https://developer.payscout.com/three_column_template#getting-started
 * Also see our blog http://www.affinityops.com
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

	/**
	* Set Billing Information
	**/
	public function setBilling(
		$first_name,
		$last_name,
		$address_line_1,
		$address_line_2,
		$city,
		$state,
		$postal_code,
		$country,
		$email_address,
		$phone_number
			){
		$this->billing['first_name']				= (strlen($first_name) > 64) ? substr($first_name, 0, 64) : $first_name;
	    $this->billing['last_name']					= (strlen($last_name) > 64) ? substr($last_name, 0, 64) : $last_name;
	    $this->billing['phone_number']				= (strlen($phone_number) > 32) ? substr($phone_number, 0, 32) : $phone_number;
	    $this->billing['address_line_1']			= (strlen($address_line_1) > 64) ? substr($address_line_1, 0, 64) : $address_line_1;
	    $this->billing['address_line_2']			= (strlen($address_line_2) > 64) ? substr($address_line_2, 0, 64) : $address_line_2;
	    $this->billing['city']						= (strlen($city) > 64) ? substr($city, 0, 64) : $city;
	    $this->billing['state']						= (strlen($state) > 2) ? substr($state, 0, 2) : $state;
	    $this->billing['postal_code']       		= (strlen($postal_code) > 16) ? substr($postal_code, 0, 16) : $postal_code;
	    $this->billing['country']   				= (strlen($country) > 3) ? substr($country, 0, 3) : $country;
	    $this->billing['email_address']				= (strlen($email_address) > 256) ? substr($email_address, 0, 256) : $email_address;
	}

	/**
	* Set Shipping Information
	**/
	public function setShipping(
		$s_first_name,
		$s_last_name,
		$s_email_address,
		$s_cell_phone_number,
		$s_phone_number,
		$s_address_line_1,
		$s_address_line_2,
		$s_city,
		$s_state,
		$s_postal_code,
		$s_country
			){
		$this->shipping['first_name']				= $s_first_name;
	    $this->shipping['last_name']				= $s_last_name;
	    $this->shipping['email_address']			= $s_email_address;
	    $this->shipping['cell_phone_number']		= $s_cell_phone_number;
	    $this->shipping['phone_number']				= $s_phone_number;
	    $this->shipping['address_line_1']			= $s_address_line_1;
	    $this->shipping['address_line_2']			= $s_address_line_2;
	    $this->shipping['city']						= $s_city;
	    $this->shipping['state']					= $s_state;
	    $this->shipping['postal_code']       		= $s_postal_code;
	    $this->shipping['country']   				= $s_country;
	}                       

	/**
	* Set IP Address
	**/
	public function setIP($ip_address)
	{
		$this->ip['ip_address'] = $ip_address;
	}

	/**
	* Set level 2
	**/
	public function setLevel2($invoiceNumber)
	{
		$this->level2['invoice_number'] = $invoiceNumber;
	}

	/**
	* Process Sale https://developer.payscout.com/three_column_template#sale-example
	**/
	public function sale($account_number, $expiration_month, $expiration_year, $amount, $cvv2='')
	{
		// Build arrayData
		$arrayData = array(
			"client_username"			=> $this->login['client_username'],
		    "client_password"			=> $this->login['client_password'],
		    "client_token"				=> $this->login['client_token'],
		    "action"					=> 'SALE',
		    // build billing query
		    "billing_first_name"		=> $this->billing['first_name'],
		    "billing_last_name"			=> $this->billing['last_name'],
		    "billing_address_line_1"	=> $this->billing['address_line_1'],
		    "billing_address_line_2"	=> $this->billing['address_line_2'],
		    "billing_city"				=> $this->billing['city'],
		    "billing_state"				=> $this->billing['state'],
		    "billing_postal_code"		=> $this->billing['postal_code'],
		    "billing_country"			=> $this->billing['country'],
		    "billing_phone_number"		=> $this->billing['phone_number'],
		    "billing_email_address"		=> $this->billing['email_address'],
		    
		    "account_number"		=> $account_number,
		    "expiration_month"		=> $expiration_month,
		    "expiration_year"		=> $expiration_year,
		    
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD'
		);

		$arrayData['ip_address'] = (!empty($ip['ip_address'])) ? $ip['ip_address'] : '';

		if(!empty($cvv2)) { $arrayData['cvv2']	= $cvv2; }

		$arrayData['shipping_first_name']			= (!empty($this->shipping['first_name'])) ? $this->shipping['first_name'] : '';
		$arrayData['shipping_last_name']			= (!empty($this->shipping['last_name'])) ? $this->shipping['last_name'] : '';
		$arrayData['shipping_email_address']		= (!empty($this->shipping['email_address'])) ? $this->shipping['email_address'] : '';
		$arrayData['shipping_cell_phone_number']	= (!empty($this->shipping['cell_phone_number'])) ? $this->shipping['cell_phone_number'] : '';
		$arrayData['shipping_phone_number']			= (!empty($this->shipping['phone_number'])) ? $this->shipping['phone_number'] : '';
		$arrayData['shipping_address_line_1']		= (!empty($this->shipping['address_line_1'])) ? $this->shipping['address_line_1'] : '';
		$arrayData['shipping_address_line_2']		= (!empty($this->shipping['address_line_2'])) ? $this->shipping['address_line_2'] : '';
		$arrayData['shipping_city']					= (!empty($this->shipping['city'])) ? $this->shipping['city'] : '';
		$arrayData['shipping_state']				= (!empty($this->shipping['state'])) ? $this->shipping['state'] : '';
		$arrayData['shipping_postal_code']			= (!empty($this->shipping['postal_code'])) ? $this->shipping['postal_code'] : '';
		$arrayData['shipping_country']				= (!empty($this->shipping['country'])) ? $this->shipping['country'] : '';
		
		$arrayData['billing_invoice_number'] = (!empty($this->level2['invoice_number'])) ? $this->level2['invoice_number'] : '';
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Process Auth https://developer.payscout.com/three_column_template#auth-example
	**/
	public function auth($account_number, $expiration_month, $expiration_year, $amount, $cvv2='')
	{
		// Build arrayData
		$arrayData = array(
			"client_username"			=> $this->login['client_username'],
		    "client_password"			=> $this->login['client_password'],
		    "client_token"				=> $this->login['client_token'],
		    "action"					=> 'PRE_AUTHORIZATION',
		    // build billing query
		    "billing_first_name"		=> $this->billing['first_name'],
		    "billing_last_name"			=> $this->billing['last_name'],
		    "billing_address_line_1"	=> $this->billing['address_line_1'],
		    "billing_address_line_2"	=> $this->billing['address_line_2'],
		    "billing_city"				=> $this->billing['city'],
		    "billing_state"				=> $this->billing['state'],
		    "billing_postal_code"		=> $this->billing['postal_code'],
		    "billing_country"			=> $this->billing['country'],
		    "billing_phone_number"		=> $this->billing['phone_number'],
		    "billing_email_address"		=> $this->billing['email_address'],
		    
		    "account_number"		=> $account_number,
		    "expiration_month"		=> $expiration_month,
		    "expiration_year"		=> $expiration_year,
		    
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD'
		);

		$arrayData['ip_address'] = (!empty($ip['ip_address'])) ? $ip['ip_address'] : '';

		if(!empty($cvv2)) { $arrayData['cvv2']	= $cvv2; }

		$arrayData['shipping_first_name']			= (!empty($this->shipping['first_name'])) ? $this->shipping['first_name'] : '';
		$arrayData['shipping_last_name']			= (!empty($this->shipping['last_name'])) ? $this->shipping['last_name'] : '';
		$arrayData['shipping_email_address']		= (!empty($this->shipping['email_address'])) ? $this->shipping['email_address'] : '';
		$arrayData['shipping_cell_phone_number']	= (!empty($this->shipping['cell_phone_number'])) ? $this->shipping['cell_phone_number'] : '';
		$arrayData['shipping_phone_number']			= (!empty($this->shipping['phone_number'])) ? $this->shipping['phone_number'] : '';
		$arrayData['shipping_address_line_1']		= (!empty($this->shipping['address_line_1'])) ? $this->shipping['address_line_1'] : '';
		$arrayData['shipping_address_line_2']		= (!empty($this->shipping['address_line_2'])) ? $this->shipping['address_line_2'] : '';
		$arrayData['shipping_city']					= (!empty($this->shipping['city'])) ? $this->shipping['city'] : '';
		$arrayData['shipping_state']				= (!empty($this->shipping['state'])) ? $this->shipping['state'] : '';
		$arrayData['shipping_postal_code']			= (!empty($this->shipping['postal_code'])) ? $this->shipping['postal_code'] : '';
		$arrayData['shipping_country']				= (!empty($this->shipping['country'])) ? $this->shipping['country'] : '';
		
		$arrayData['billing_invoice_number'] = (!empty($this->level2['invoice_number'])) ? $this->level2['invoice_number'] : '';
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Process Credit https://developer.payscout.com/three_column_template#credit-example
	**/
	public function credit($account_number, $expiration_month, $expiration_year, $amount, $cvv2='')
	{
		// Build arrayData
		$arrayData = array(
			"client_username"			=> $this->login['client_username'],
		    "client_password"			=> $this->login['client_password'],
		    "client_token"				=> $this->login['client_token'],
		    "action"					=> 'CREDIT',
		    
		    "account_number"		=> $account_number,
		    "expiration_month"		=> $expiration_month,
		    "expiration_year"		=> $expiration_year,
		    
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD'
		);

		if(!empty($cvv2)) { $arrayData['cvv2']	= $cvv2; }
    	
    	return $this->_Post($arrayData);
	}

	/**
	* Process Capture https://developer.payscout.com/three_column_template#capture-example
	**/
	public function capture($transaction_id, $amount)
	{
		// Build arrayData
		$arrayData = array(
			"client_username"	=> $this->login['client_username'],
		    "client_password"	=> $this->login['client_password'],
		    "client_token"		=> $this->login['client_token'],
		    "action"			=> 'CAPTURE',
		    
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD',
		    "original_transaction_id"	=> $transaction_id
		);

		return $this->_Post($arrayData);
	}

	/**
	* Process Void https://developer.payscout.com/three_column_template#void-example
	**/
	public function void($transaction_id)
	{
		// Build arrayData
		$arrayData = array(
			"client_username"	=> $this->login['client_username'],
		    "client_password"	=> $this->login['client_password'],
		    "client_token"		=> $this->login['client_token'],
		    "action"			=> 'VOID',

		    "original_transaction_id"	=> $transaction_id
		);

		return $this->_Post($arrayData);
	}

	/**
	* Process Refund https://developer.payscout.com/three_column_template#refund-example
	**/
	public function refund($transaction_id, $amount)
	{
		// Build arrayData
		$arrayData = array(
			"client_username"	=> $this->login['client_username'],
		    "client_password"	=> $this->login['client_password'],
		    "client_token"		=> $this->login['client_token'],
		    "action"			=> 'REFUND',
		    
		    "initial_amount"	=> $amount,
		    "currency"			=> 'USD',
		    "original_transaction_id"	=> $transaction_id
		);

		return $this->_Post($arrayData);
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
		// Use payment method id 3715
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
