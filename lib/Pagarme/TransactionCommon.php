<?php
class PagarMe_TransactionCommon extends PagarMe_Model 
{

	public function __construct($response = array())  {
		parent::__construct($response);			
		if(!isset($this->payment_method)) {
			$this->payment_method = 'credit_card';
		}

		if(!isset($this->status)) {
			$this->status = 'local';
		}
	} 

	public function generateCardHash() 
	{
		$request = new PagarMe_Request('/transactions/card_hash_key','GET');
		$response = $request->run();
		$key = openssl_get_publickey($response['public_key']);
		$params = array(
			"card_number" => $this->card_number,
			"card_holder_name" => $this->card_holder_name,
			"card_expiration_date" => $this->card_expiration_month . $this->card_expiration_year,
			"card_cvv" => $this->card_cvv
		);
		$str = "";
		foreach($params as $k => $v) {
			$str .= $k . "=" . $v . "&";	
		}
		$str = substr($str, 0, -1);
		openssl_public_encrypt($str,$encrypt, $key);
		return $response['id'].'_'.base64_encode($encrypt);
	}

	public static function calculateInstallmentsAmount($amount, $interest_rate, $max_installments) {
		$request = new PagarMe_Request(self::getUrl() . '/calculate_installments_amount', 'GET');
		$params = array('amount' => $amount, 'interest_rate' => $interest_rate, 'max_installments' => $max_installments);	
		$request->setParameters($params);
		$response = $request->run();
		
		return $response;
	}

	public function create() {
			if(!$this->card_hash && $this->payment_method == 'credit_card') {
				$this->card_hash = $this->generateCardHash();
			} 
		
			if($this->card_hash) {
				unset($this->card_holder_name);
				unset($this->card_number);
				unset($this->card_expiration_month);
				unset($this->card_expiration_year);
				unset($this->card_cvv);
			}
			parent::create();
	}
}
?>
