<?php

namespace PagarMe\Sdk\Customer\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Customer\Document\DocumentCollection;

class CustomerCreate implements RequestInterface
{
    use \PagarMe\Sdk\DocumentSerializer;
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
     * @var DocumentCollection | Números de documentos
     */
    private $documents;

    /**
     * @param string $name
     * @param string $email
     */
    public function __construct(
        $name,
        $email,
        $externalId,
        $type,
        $country,
        $phoneNumbers,
        DocumentCollection $documents = null
    ) {
        $this->name         = $name;
        $this->email        = $email;
        $this->externalId   = $externalId;
        $this->type         = $type;
        $this->country      = $country;
        $this->phoneNumbers = $phoneNumbers;
        $this->documents    = $documents;
    }

    /**
     *  @return array
     */
    public function getPayload()
    {
        $customerData = [
            'name'            => $this->name,
            'email'           => $this->email,
            'external_id'     => $this->externalId,
            'type'            => $this->type,
            'country'         => $this->country,
            'phone_numbers'   => $this->phoneNumbers,
        ];

        if ($this->documents instanceof DocumentCollection) {
            $customerData['documents'] = $this->getDocumentsInfo(
                $this->documents
            );
        }

        return $customerData;
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
}
