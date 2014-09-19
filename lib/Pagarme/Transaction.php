<?php
class PagarMe_Transaction extends PagarMe_TransactionCommon {

	public function charge() 
	{
		$this->create();
	}

	public function capture($amount = false) {
			$request = new PagarMe_Request(self::getUrl().'/'.$this->id . '/capture', 'POST');
			if($amount) { 
				$request->setParameters(array('amount' => $amount));
			}
			$response = $request->run();
			$this->refresh($response);
	}

	public function refund() 
	{
			$request = new PagarMe_Request(self::getUrl().'/'.$this->id . '/refund', 'POST');
			$response = $request->run();
			$this->refresh($response);
	}
}

?>
