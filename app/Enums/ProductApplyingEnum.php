<?php

namespace App\Enums;

enum ProductApplyingEnum: string
{
    case CITIZEN = 'citizen';
    case MILITARY = 'military';
    case POLICEMAN = 'policeman';
//    case LAWYER = 'lawyer';

    /**
     * Отримати всі значення enum
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Отримати всі назви enum
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Отримати підпис для відображення
     */
    public function label(): string
    {
        return match($this) {
            self::CITIZEN => 'Громадянин',
            self::MILITARY => 'Військовий',
            self::POLICEMAN => 'Поліцейський',
            self::LAWYER => 'Юрист',
        };
    }
}
