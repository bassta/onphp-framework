<?php

namespace onPHP\main\Math;

use onPHP\core\Base\Singleton;

/***************************************************************************
 *   Copyright (C) 2007 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @ingroup Math
 **/
abstract class BigNumberFactory extends Singleton
{
    /**
     * @return BigInteger
     **/
    public abstract function makeNumber($number, $base = 10);

    /**
     * make number from big-endian signed two's complement binary notation
     * @return BigInteger
     **/
    public abstract function makeFromBinary($binary);

    /**
     * @param $stop maximum random number
     * @return BigInteger
     **/
    public abstract function makeRandom($stop, RandomSource $source);
}