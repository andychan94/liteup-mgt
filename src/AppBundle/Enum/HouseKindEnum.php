<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 08/05/2018
 * Time: 01:48
 */

namespace AppBundle\Enum;


abstract class HouseKindEnum
{
    const TYPE_BUNGALOW = "bungalow";
    const TYPE_COMMERCIAL = "commercial";
    const TYPE_DUPLEX = "duplex";
    const TYPE_FLAT = "flat";
    const TYPE_HOUSE = "house";
    const TYPE_LAND = "land";
    const TYPE_OFFICE = "office";
    const TYPE_SELF = "self";
    const TYPE_SHOP = "shop";

    /** @var array user friendly named type */
    protected static $typeName = [

        self::TYPE_BUNGALOW => 'Bungalow',
        self::TYPE_COMMERCIAL => 'Commercial Property',
        self::TYPE_DUPLEX => 'Duplex',
        self::TYPE_FLAT => 'Flat',
        self::TYPE_HOUSE => 'House',
        self::TYPE_LAND => 'Land',
        self::TYPE_OFFICE => 'Office space',
        self::TYPE_SELF => 'Self contain',
        self::TYPE_SHOP => 'Shop',
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
            self::TYPE_BUNGALOW,
            self::TYPE_COMMERCIAL,
            self::TYPE_DUPLEX,
            self::TYPE_FLAT,
            self::TYPE_HOUSE,
            self::TYPE_LAND,
            self::TYPE_OFFICE,
            self::TYPE_SELF,
            self::TYPE_SHOP
        ];
    }

}