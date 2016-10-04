<?php

namespace onphp\core\OSQL;

use onphp\core\Base\Assert;
use onphp\core\Base\Enumeration;
use onphp\core\DB\Dialect;
use onphp\core\Exceptions\WrongStateException;

/***************************************************************************
 *   Copyright (C) 2006-2008 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * Generic SQL data types.
 *
 * @ingroup OSQL
 **/
final class DataType extends Enumeration implements DialectString
{
    const SMALLINT = 4097;
    const INTEGER = 4098;
    const BIGINT = 4099;
    const NUMERIC = 5892;
    const REAL = 4357;
    const DOUBLE = 4358;
    const BOOLEAN = 7;
    const CHAR = 264;
    const VARCHAR = 265;
    const TEXT = 10;
    const DATE = 11;
    const TIME = 2572;
    const TIMESTAMP = 2573;
    const TIMESTAMPTZ = 2574;
    const INTERVAL = 15;
    const BINARY = 14;
    const IP = 16;
    const IP_RANGE = 17;
    const HAVE_SIZE = 256;
    const HAVE_PRECISION = 512;
    const HAVE_SCALE = 1024;
    const HAVE_TIMEZONE = 2048;
    const CAN_BE_UNSIGNED = 4096;
    private $size = null;
    private $precision = null;
    private $scale = null;
    private $null = true;
    private $timezone = false;
    private $unsigned = false;
    protected $names = array(self::SMALLINT => 'SMALLINT', self::INTEGER => 'INTEGER', self::BIGINT => 'BIGINT', self::NUMERIC => 'NUMERIC', self::REAL => 'FLOAT', self::DOUBLE => 'DOUBLE PRECISION', self::BOOLEAN => 'BOOLEAN', self::CHAR => 'CHARACTER', self::VARCHAR => 'CHARACTER VARYING', self::TEXT => 'TEXT', self::DATE => 'DATE', self::TIME => 'TIME', self::TIMESTAMP => 'TIMESTAMP', self::TIMESTAMPTZ => 'TIMESTAMP', self::INTERVAL => 'INTERVAL', self::BINARY => 'BINARY', self::IP => 'IP', self::IP_RANGE => 'IP_RANGE');

    /**
     * @return DataType
     **/
    public static function create($id)
    {
        return new self($id);
    }

    public static function getAnyId()
    {
        return self::BOOLEAN;
    }

    public function getSize()
    {
        return $this->size;
    }

    /**
     * @throws WrongArgumentException
     * @return DataType
     **/
    public function setSize($size)
    {
        Assert::isInteger($size);
        Assert::isTrue($this->hasSize());
        $this->size = $size;
        return $this;
    }

    public function hasSize()
    {
        return (bool)($this->id & self::HAVE_SIZE);
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @throws WrongArgumentException
     * @return DataType
     **/
    public function setPrecision($precision)
    {
        Assert::isInteger($precision);
        Assert::isTrue(($this->id & self::HAVE_PRECISION) > 0);
        $this->precision = $precision;
        return $this;
    }

    public function hasPrecision()
    {
        return (bool)($this->id & self::HAVE_PRECISION);
    }

    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @throws WrongArgumentException
     * @return DataType
     **/
    public function setScale($scale)
    {
        Assert::isInteger($scale);
        Assert::isTrue(($this->id & self::HAVE_SCALE) > 0);
        $this->scale = $scale;
        return $this;
    }

    /**
     * @throws WrongArgumentException
     * @return DataType
     **/
    public function setTimezoned($zoned = false)
    {
        Assert::isTrue(($this->id & self::HAVE_TIMEZONE) > 0);
        $this->timezone = true === $zoned;
        return $this;
    }

    public function isTimezoned()
    {
        return $this->timezone;
    }

    /**
     * @return DataType
     **/
    public function setNull($isNull = false)
    {
        $this->null = $isNull === true;
        return $this;
    }

    public function isNull()
    {
        return $this->null;
    }

    /**
     * @throws WrongArgumentException
     * @return DataType
     **/
    public function setUnsigned($unsigned = false)
    {
        Assert::isTrue(($this->id && self::CAN_BE_UNSIGNED) > 0);
        $this->unsigned = $unsigned === true;
        return $this;
    }

    public function isUnsigned()
    {
        return $this->unsigned;
    }

    public function toDialectString(Dialect $dialect)
    {
        $out = $dialect->typeToString($this);
        if ($this->unsigned) {
            $out .= ' UNSIGNED';
        }
        if ($this->id & self::HAVE_PRECISION) {
            if ($this->precision) {
                switch ($this->id) {
                    case self::TIME:
                    case self::TIMESTAMP:
                        $out .= "({$this->precision})";
                        break;
                    case self::NUMERIC:
                        $out .= $this->precision ? "({$this->size}, {$this->precision})" : "({$this->size})";
                        break;
                    default:
                        throw new WrongStateException();
                }
            }
        } elseif ($this->hasSize()) {
            if (!$this->size) {
                throw new WrongStateException("type '{$this->name}' must have size");
            }
            $out .= "({$this->size})";
        }
        if ($this->id & self::HAVE_TIMEZONE) {
            $out .= $dialect->timeZone($this->timezone);
        }
        $out .= $this->null ? ' NULL' : ' NOT NULL';
        return $out;
    }
}