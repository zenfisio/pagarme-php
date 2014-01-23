<?php

class PagarMe_TransactionTest extends PagarMeTestCase {

	public function testCharge() {
		$transaction = self::createTestTransaction();
		$this->assertFalse($transaction->getId());
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
	}

	public function testAntifraudTransaction() {
		$t = self::createTestTransactionWithCustomer();
		$t->charge();
		$this->validateTransactionResponse($t);
	}

	public function testPostbackUrl() {
		$t = self::createTestTransaction();	
		$t->setPostbackUrl('http://url.com');
		$t->charge();

		$this->assertEqual($t->getStatus(), 'processing');
	}
	
	public function testPostbackUrlWithCardHash() {
		$t = self::createTestTransactionWithCustomer();
		$card_hash = $t->generateCardHash();

		$t->setPostbackUrl('http://url.com');
		$t->charge();

		$this->validateTransactionResponse($t);

		$this->assertEqual($t->getPostbackUrl(), 'http://url.com');
		$this->assertEqual($t->getStatus(), 'processing');
	}

	public function testChargeWithCardHash() {
		$t = self::createTestTransactionWithCustomer();
		$card_hash = $t->generateCardHash();

		$transaction = self::createTestTransactionWithCustomer();
		$transaction->setCardHash($card_hash);
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
	}

	public function testTransactionWithBoleto() {
		authorizeFromEnv();
		$t1 = self::createTestTransaction();
		$t1->setPaymentMethod('boleto');
		$t1->charge();

		$this->validateTransactionResponse($t1);

		$t2 = self::createTestTransactionWithCustomer();
		$t2->setPaymentMethod('boleto');
		$t2->charge();

		$this->validateTransactionResponse($t2);


		$this->assertEqual($t2->getPaymentMethod(), 'boleto');
	}

	public function testPostback() {
		$transaction = self::createTestTransaction();
		$transaction->setPostbackUrl('abc2');

		$this->assertEqual('abc2', $transaction->getPostbackUrl());
	}

	public function testCreationWithFraud() {
		authorizeFromEnv();
		self::setAntiFraud("false");
		$transaction = new PagarMe_Transaction(array(
			'card_number' => '4111111111111111', 
			'card_holder_name' => "Jose da silva", 
			'card_expiration_month' => 11, 
			'card_expiration_year' => "2014", 
			'card_cvv' => 356, 
			'customer' => array(
				'name' => "Jose da Silva",  
				'document_number' => "36433809847", 
				'email' => "henrique@pagar.me", 
				'address' => array(
					'street' => "Av Faria Lima",
					'neighborhood' => 'Jardim Europa',
					'zipcode' => '12460000', 
					'street_number' => 295, 
				),
				'phone' => array(
					'ddd' => 12, 
					'number' => '981433533', 
				),
				'sex' => 'M', 
				'born_at' => '1995-10-11')
			));

		$transaction->setInstallments(6); // Número de parcelas
		$transaction->setAmount('1000'); // Set Amount

		$transaction->charge();
		$this->assertEqual($transaction->getStatus(), 'paid');
		$this->assertEqual($transaction->getAmount(), '1000');

		$this->assertTrue($transaction->getCardBrand());
		$this->assertEqual($transaction->getCardBrand(), 'visa');

		$this->assertEqual($transaction->getInstallments(), 6);

		$this->assertTrue($transaction->getId());
		$this->assertFalse($transaction->getRefuseReason());
		$this->assertTrue($transaction->getCustomer());

		$this->assertTrue($transaction->getCustomer()->getPhones());
		$this->assertTrue($transaction->getCustomer()->getAddresses());

		$this->assertEqual($transaction->getCustomer()->getName(), 'Jose da Silva');
		$this->assertTrue($transaction->getCustomer()->getName());
		$this->assertTrue($transaction->getCustomer()->getDocumentNumber());
		$this->assertTrue($transaction->getCustomer()->getDocumentType());
		$this->assertTrue($transaction->getCustomer()->getEmail());
		$this->assertTrue($transaction->getCustomer()->getGender());
		$this->assertTrue($transaction->getCustomer()->getId());


		self::setAntiFraud("false");
	}

	public function testRefund() {
		$transaction = self::createTestTransaction();
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
		$transaction->refund();
		$this->assertEqual($transaction->getStatus(), 'refunded');

		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->refund();
	}

	public function testCreation() {
		$transaction = self::createTestTransaction();
		$this->assertEqual($transaction->getStatus(), 'local');
		$this->assertEqual($transaction->getPaymentMethod(), 'credit_card');
	} 

	public function testMetadata() {
		$transaction = self::createTestTransaction();
		$transaction->setMetadata(array('event' => array('name' => "Evento irado", 'quando'=> 'amanha')));
		$transaction->charge();
		$this->assertTrue($transaction->getId());

		$transaction2 = PagarMe_Transaction::findById($transaction->getId());
		$metadata = $transaction2->getMetadata();
		$this->assertEqual($metadata['event']['name'], "Evento irado");
	}

	public function testDeepMetadata() {
		$transaction = self::createTestTransaction();
		$transaction->setMetadata(array('basket' => array('session' => array('date' => "31/04/2014", 'time' => "12:00:00"), 'ticketTypeId'=> '5209', 'type' => "inteira", 'quantity' => '1', 'price' => 2000)));
		$transaction->charge();
		$this->assertTrue($transaction->getId());

		$transaction2 = PagarMe_Transaction::findById($transaction->getId());
		$metadata = $transaction2->getMetadata();
		$this->assertEqual($metadata['basket']['quantity'], "1");
		$this->assertEqual($metadata['basket']['session']['date'], "31/04/2014");
	}

	public function testValidation() {
		$transaction = new PagarMe_Transaction();
		$transaction->setCardNumber("123");
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCardNumber('4111111111111111');

		$transaction->setCardHolderName('');
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCardHolderName("Jose da silva");

		$transaction->setExpiracyMonth(13);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setExpiracyMonth(12);

		$transaction->setExpiracyYear(10);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setExpiracyYear(16);

		$transaction->setCvv(123456);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCvv(123);

		$transaction->setAmount(0);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setAmount(1000);
	}


	public function testFingerprint() {
		$this->assertTrue(PagarMe::validateFingerprint('13', sha1('13' . '#' . PagarMe::getApiKey())));		
	}
}

?>
