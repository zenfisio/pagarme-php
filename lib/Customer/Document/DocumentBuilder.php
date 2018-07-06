<?php

namespace PagarMe\Sdk\Customer\Document;

trait DocumentBuilder
{
    /**
     * @param array $DocumentData
     * @return DocumentCollection
     */
    private function buildDocuments($DocumentData)
    {
        $documents = new DocumentCollection();

        if (is_array($DocumentData)) {
            foreach ($DocumentData as $document) {
                $documents[] = new Document(get_object_vars($document));
            }
        }

        return $documents;
    }
}
