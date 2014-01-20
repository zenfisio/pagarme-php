<?php
class PagarMe_Address extends PagarMe_Model {
	private $street, $city, $state, $neighborhood, $zipcode, $id, $street_number, $country;

	public function __construct($serverResponse) {
			$this->updateFieldsFromResponse($serverResponse);
	}

	public function updateFieldsFromResponse($serverResponse) {
		$this->id = (isset($serverResponse['id'])) ? $serverResponse['id'] : 0;
		$this->street = (isset($serverResponse['street'])) ? $serverResponse['street'] : 0;
		$this->city = (isset($serverResponse['city'])) ? $serverResponse['city'] : 0;
		$this->state = (isset($serverResponse['state'])) ? $serverResponse['state'] : 0;
		$this->neighborhood = (isset($serverResponse['neighborhood'])) ? $serverResponse['neighborhood'] : 0;
		$this->zipcode = (isset($serverResponse['zipcode'])) ? $serverResponse['zipcode'] : 0;
		$this->complementary = (isset($serverResponse['complementary'])) ? $serverResponse['complementary'] : 0;
		$this->street_number = (isset($serverResponse['street_number'])) ? $serverResponse['street_number'] : 0;
		$this->country = (isset($serverResponse['country'])) ? $serverResponse['country'] : 0;
	}

	public function setStreet($street) {$this->street = $street;}
	public function getStreet() { return $this->street;}

	public function setCity($city) {$this->city = $city;}
	public function getCity() { return $this->city;}

	public function setState($state) {$this->state = $state;}
	public function getState() { return $this->state;}

	public function setNeighborhood($neighborhood) {$this->neighborhood = $neighborhood;}
	public function getNeighborhood() { return $this->neighborhood;}

	public function setZipcode($zipcode) {$this->zipcode = $zipcode;}
	public function getZipcode() { return $this->zipcode;}

	public function getId() { return $this->id;}

	public function setComplementary($complementary) {$this->complementary = $complementary;}
	public function getComplementary() { return $this->complementary;}

	public function setStreetNumber($street_number) {$this->street_number = $street_number;}
	public function getStreetNumber() { return $this->street_number;}

	public function setCountry($country) {$this->country = $country;}
	public function getCountry() { return $this->country;}
}
?>
