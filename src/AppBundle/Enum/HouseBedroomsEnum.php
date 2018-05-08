<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 08/05/2018
 * Time: 01:48
 */

namespace AppBundle\Enum;


abstract class HouseBedroomsEnum
{
    const ZERO = 0;
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;
    const SEVENPLUS = 7;

    /** @var array user friendly named type */
    protected static $typeName = [
        self::ZERO => '0',
        self::ONE => '1',
        self::TWO => '2',
        self::THREE => '3',
        self::FOUR => '4',
        self::FIVE => '5',
        self::SIX => '6',
        self::SEVENPLUS => '7+',
    ];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::ZERO,
            self::ONE,
            self::TWO,
            self::THREE,
            self::FOUR,
            self::FIVE,
            self::SIX,
            self::SEVENPLUS,
        ];
    }

}