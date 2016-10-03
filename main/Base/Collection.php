<?php

namespace onPHP\main\Base;

/***************************************************************************
 *   Copyright (C) 2008 by Denis M. Gabaidulin                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
interface Collection
{
    public function add(CollectionItem $item);

    public function addAll(array $items);

    public function clear();

    public function contains(CollectionItem $item);

    public function containsAll(array $items);

    public function isEmpty();

    public function size();

    public function remove(CollectionItem $item);

    public function removeAll(array $items);

    public function retainAll(array $items);

    public function getList();

    public function getByName($name);

    public function has($name);
}