<?php
class PagarMe_Subscription extends PagarMe_TransactionCommon {



	public function create() {
		if($this->plan) {
			$this->plan_id = $this->plan->id;
			unset($this->plan);
		}
		parent::create();
	}


	public function cancel() {
		try {
			$request = new PagarMe_Request(self::getUrl() . '/' . $this->id . '/cancel', 'POST');
			$response = $request->run();
			$this->refresh($response);
		} catch(Exception $e) {
			throw new PagarMe_Exception($e->getMessage());
		}
	}

	public function charge($amount) {
		try {
			$this->amount = $amount;
			$request = new PagarMe_Request(self::getUrl(). '/' . $this->id, 'POST');
			$request->setParameters($this->unsavedArray());
			$response = $request->run();
			$this->refresh($response);
		} catch(Exception $e) {
			throw new PagarMe_Exception($e->getMessage());
		}
	}
}
?>
