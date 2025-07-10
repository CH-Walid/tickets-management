<?php

namespace App\Enums;

enum RolesEnum: string
{
    case USER_SIMPLE = 'user_simple';
    case CHEF_TECHNICIEN = 'chef_technicien';
    case TECHNICIEN = 'technicien';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
