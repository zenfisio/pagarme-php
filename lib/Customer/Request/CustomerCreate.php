<?php

namespace PagarMe\Sdk\Customer\Request;

use PagarMe\Sdk\RequestInterface;
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
     * @var Phone | Telefone do comprador
     */
    private $phone;

    /**
     * @param string $name
     * @param string $email
     * @param Phone $phone
     */
    public function __construct(
        $name,
        $email,
        $externalId,
        $type,
        $country,
        $phoneNumbers,
        $documents,
        Phone $phone
    ) {
        $this->name           = $name;
        $this->email          = $email;
        $this->externalId     = $externalId;
        $this->type           = $type;
        $this->country        = $country;
        $this->phoneNumbers   = $phoneNumbers;
        $this->documents      = $documents;
        $this->phone          = $phone;
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
            'phone'           => $this->getPhoneData()
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
