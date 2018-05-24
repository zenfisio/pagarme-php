<?php

namespace PagarMe\Sdk\Customer\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;

class CustomerCreate implements RequestInterface
{
     /**
     * @var string | Nome ou razão social do comprador
     */
    private $name;

     /**
     * @var string | Tipo de documento PF ou PJ
     */
    private $type;

     /**
     * @var string | Identificador do cliente na loja
     */
    private $externalId;

     /**
     * @var string | País
     */
    private $country;

     /**
     * @var array | Números de telefone
     */
    private $phoneNumbers;

     /**
     * @var string | E-mail do comprador
     */
    private $email;

     /**
     * @var array | Números de documentos
     */
    private $documents;

     /**
     * @var Address | Endereço do comprador
     */
    private $address;

     /**
     * @var Phone | Telefone do comprador
     */
    private $phone;

     /**
     * @var string | Data de nascimento ex: '13121988'
     */
    private $bornAt;

     /**
     * @var string | Gênero
     */
    private $gender;

    /**
     * @param string $name
     * @param string $email
     * @param Address $address
     * @param Phone $phone
     * @param string $bornAt
     * @param string $gender
     */
    public function __construct(
        $name,
        $email,
        $externalId,
        $type,
        $country,
        $phoneNumbers,
        $documents,
        Address $address,
        Phone $phone,
        $bornAt,
        $gender
    ) {
        $this->name           = $name;
        $this->email          = $email;
        $this->externalId     = $externalId;
        $this->type           = $type;
        $this->country        = $country;
        $this->phoneNumbers   = $phoneNumbers;
        $this->documents      = $documents;
        $this->address        = $address;
        $this->phone          = $phone;
        $this->bornAt         = $bornAt;
        $this->gender         = $gender;
    }

    /**
     *  @return array
     */
    public function getPayload()
    {
        return [
            'name'            => $this->name,
            'email'           => $this->email,
            'external_id'     => $this->externalId,
            'type'            => $this->type,
            'country'         => $this->country,
            'phone_numbers'   => $this->phoneNumbers,
            'documents'       => $this->getDocumentsData(),
            'address'         => $this->getAddresssData(),
            'phone'           => $this->getPhoneData(),
            'born_at'         => $this->bornAt,
            'gender'          => $this->gender
        ];
    }

    /**
     *  @return string
     */
    public function getPath()
    {
        return 'customers';
    }

    /**
     *  @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     *  @return array
     */
    private function getAddresssData()
    {
        $addressData = [
            'street'        => $this->address->getStreet(),
            'street_number' => $this->address->getStreetNumber(),
            'neighborhood'  => $this->address->getNeighborhood(),
            'zipcode'       => $this->address->getZipcode()
        ];

        if (!is_null($this->address->getComplementary())) {
            $addressData['complementary'] = $this->address->getComplementary();
        }

        if (!is_null($this->address->getCity())) {
            $addressData['city'] = $this->address->getCity();
        }

        if (!is_null($this->address->getState())) {
            $addressData['state'] = $this->address->getState();
        }

        if (!is_null($this->address->getCountry())) {
            $addressData['country'] = $this->address->getCountry();
        }

        return $addressData;
    }

    /**
     *  @return array
     */
    private function getDocumentsData()
    {
        $documentsData = array_map(function ($document) {
            return [
                'type' => $document->getType(),
                'number' => $document->getNumber()
            ];
        }, $this->documents);

        return $documentsData;
    }

    /**
     *  @return array
     */
    private function getPhoneData()
    {
        $phoneData = [
            'ddd'    => $this->phone->getDdd(),
            'number' => $this->phone->getNumber()
        ];

        if (!is_null($this->phone->getDdi())) {
            $phoneData['ddi'] = $this->phone->getDdi();
        }

        return $phoneData;
    }
}
