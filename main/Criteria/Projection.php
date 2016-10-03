<?php

namespace onPHP\main\Criteria;

use onPHP\core\Base\StaticFactory;
use onPHP\core\Logic\LogicalObject;
use onPHP\core\Logic\MappableObject;
use onPHP\main\Criteria\Projections\AverageNumberProjection;
use onPHP\main\Criteria\Projections\ClassProjection;
use onPHP\main\Criteria\Projections\DistinctCountProjection;
use onPHP\main\Criteria\Projections\GroupByClassProjection;
use onPHP\main\Criteria\Projections\GroupByPropertyProjection;
use onPHP\main\Criteria\Projections\HavingProjection;
use onPHP\main\Criteria\Projections\MappableObjectProjection;
use onPHP\main\Criteria\Projections\MaximalNumberProjection;
use onPHP\main\Criteria\Projections\MinimalNumberProjection;
use onPHP\main\Criteria\Projections\ProjectionChain;
use onPHP\main\Criteria\Projections\PropertyProjection;
use onPHP\main\Criteria\Projections\RowCountProjection;
use onPHP\main\Criteria\Projections\SumProjection;

/***************************************************************************
 *   Copyright (C) 2006-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @see http://www.hibernate.org/hib_docs/v3/reference/en/html/querycriteria.html#querycriteria-projection
 *
 * @ingroup Criteria
 **/
final class Projection extends StaticFactory
{
    /**
     * @return SumProjection
     **/
    public static function sum($property, $alias = null)
    {
        return new SumProjection($property, $alias);
    }

    /**
     * @return AverageNumberProjection
     **/
    public static function avg($property, $alias = null)
    {
        return new AverageNumberProjection($property, $alias);
    }

    /**
     * @return MappableObjectProjection
     **/
    public static function mappable(MappableObject $object, $alias = null)
    {
        return new MappableObjectProjection($object, $alias);
    }

    /**
     * @return MinimalNumberProjection
     **/
    public static function min($property, $alias = null)
    {
        return new MinimalNumberProjection($property, $alias);
    }

    /**
     * @return MaximalNumberProjection
     **/
    public static function max($property, $alias = null)
    {
        return new MaximalNumberProjection($property, $alias);
    }

    /**
     * @return PropertyProjection
     **/
    public static function property($property, $alias = null)
    {
        return new PropertyProjection($property, $alias);
    }

    /**
     * @return RowCountProjection
     **/
    public static function count($property = null, $alias = null)
    {
        return new RowCountProjection($property, $alias);
    }

    /**
     * @return DistinctCountProjection
     **/
    public static function distinctCount($property = null, $alias = null)
    {
        return new DistinctCountProjection($property, $alias);
    }

    /**
     * @return ProjectionChain
     **/
    public static function chain()
    {
        return new ProjectionChain();
    }

    /**
     * @return GroupByPropertyProjection
     **/
    public static function group($property)
    {
        return new GroupByPropertyProjection($property);
    }

    /**
     * @return GroupByClassProjection
     **/
    public static function groupByClass($class)
    {
        return new GroupByClassProjection($class);
    }

    /**
     * @return HavingProjection
     **/
    public static function having(LogicalObject $logic)
    {
        return new HavingProjection($logic);
    }

    /**
     * @return ClassProjection
     **/
    public static function clazz($className)
    {
        return new ClassProjection($className);
    }
}