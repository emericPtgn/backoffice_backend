<?php

namespace App\Utils;

class DocumentUpdater {
    /**
     * Met à jour les champs d'un document avec les données fournies.
     *
     * @param object $document L'objet document à mettre à jour.
     * @param array $requestDatas Les données de requête contenant les valeurs à mettre à jour.
     * @param array $fields Un tableau associatif où les clés sont les noms des champs et les valeurs sont les noms des setters.
     */
    public static function updateDocumentFields($document, array $requestDatas, array $fields) {
        foreach ($fields as $field => $setter) {
            if (array_key_exists($field, $requestDatas)) {
                $value = $requestDatas[$field];
                $document->$setter($value);
            }
        }
    }
}
