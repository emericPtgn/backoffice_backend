<?php

namespace App\Utils;

class DocumentPersister {
    public static function persistDocument($dm, $documentToPersist) {
        try {
            $dm->persist($documentToPersist);
            $dm->flush();
    
            return [
                'status' => 'success',
                'data' => $documentToPersist
            ];
        } catch (\Throwable $th) {
            // Log l'exception complète ici si nécessaire
            // logException($th);
    
            return [
                'status' => 'error',
                'message' => 'An error occurred while saving the document.'
            ];
        }
    }
}
