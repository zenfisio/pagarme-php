<?php

namespace PagarMe\Sdk;

use PagarMe\Sdk\Customer\Document\DocumentCollection;

trait DocumentSerializer
{

    /**
     * @param \PagarMe\Sdk\Document\DocumentCollection $documents
     * @return array
     */
    public function getDocumentsInfo(DocumentCollection $DocumentCollection)
    {
        $documents = [];

        foreach ($DocumentCollection as $key => $document) {
            $document = [
                'type'   => $document->getType(),
                'number' => $document->getNumber(),
            ];

            $documents[$key] = $document;
        }

        return $documents;
    }
}
