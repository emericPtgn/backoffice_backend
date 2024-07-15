<?php

namespace App\Security;

class Roles {
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_EDITEUR = 'ROLE_EDITEUR';
    public const ROLE_AUTEUR = 'ROLE_AUTEUR';
    public const ROLE_CONTRIBUTEUR = 'ROLE_CONTRIBUTEUR';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_CLIENT = 'ROLE_CLIENT';

    public static function getAvailableRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_EDITEUR,
            self::ROLE_AUTEUR,
            self::ROLE_CONTRIBUTEUR,
            self::ROLE_USER,
            self::ROLE_CLIENT,
        ];
    }

}