<?php

namespace onphp\main\OQL\Statements;

use onPHP\core\Base\Assert;
use onPHP\main\OQL\Expressions\OqlInExpression;
use onPHP\main\OQL\Expressions\OqlQueryExpression;

/****************************************************************************
 *   Copyright (C) 2009 by Vladlen Y. Koshelev                              *
 *                                                                          *
 *   This program is free software; you can redistribute it and/or modify   *
 *   it under the terms of the GNU Lesser General Public License as         *
 *   published by the Free Software Foundation; either version 3 of the     *
 *   License, or (at your option) any later version.                        *
 *                                                                          *
 ****************************************************************************/

/**
 * @ingroup OQL
 **/
final class OqlWhereClause extends OqlQueryExpressionClause
{
    /**
     * @return OqlWhereClause
     **/
    public static function create()
    {
        return new self();
    }

    protected static function checkExpression(OqlQueryExpression $expression)
    {
        if (!$expression instanceof OqlInExpression) {
            Assert::isInstance($expression->getClassName(), 'LogicalObject');
        }
    }
}