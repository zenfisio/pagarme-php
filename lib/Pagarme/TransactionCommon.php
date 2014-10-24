<?php
class PagarMe_TransactionCommon extends PagarMe_CardHashCommon 
{
	public function __construct($response = array())
	{
		parent::__construct($response);			
		if(!isset($this->payment_method)) {
			$this->payment_method = 'credit_card';
		}

		if(!isset($this->status)) {
			$this->status = 'local';
		}
	} 

	public static function calculateInstallmentsAmount($amount, $interest_rate, $max_installments)
	{
		$request = new PagarMe_Request(self::getUrl() . '/calculate_installments_amount', 'GET');
		$params = array('amount' => $amount, 'interest_rate' => $interest_rate, 'max_installments' => $max_installments);	
		$request->setParameters($params);
		$response = $request->run();
		
		return $response;
	}

	protected function shouldGenerateCardHash()
	{
		return $this->payment_method == 'credit_card';
	}
}

