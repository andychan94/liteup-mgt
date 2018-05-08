<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 08/05/2018
 * Time: 01:48
 */

namespace AppBundle\Enum;


abstract class HouseTypeEnum
{
    const TYPE_SHORT = "short";
    const TYPE_RENT = "rent";
    const TYPE_SELL = "sell";

    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_SHORT => 'Short let',
        self::TYPE_RENT => 'Rent',
        self::TYPE_SELL => 'Sell',
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
            self::TYPE_SHORT,
            self::TYPE_RENT,
            self::TYPE_SELL
        ];
    }

}