<?php

namespace onPHP\main\UI\View;

    /***************************************************************************
     *   Copyright (C) 2011 by Alexander A. Zaytsev                            *
     *                                                                         *
     *   This program is free software; you can redistribute it and/or modify  *
     *   it under the terms of the GNU Lesser General Public License as        *
     *   published by the Free Software Foundation; either version 3 of the    *
     *   License, or (at your option) any later version.                       *
     *                                                                         *
     ***************************************************************************/
/**
 * @ingroup Flow
 **/
final class EmptyGifView implements View
{
    /**
     * @return EmptyGifView
     **/
    public static function create()
    {
        return new self();
    }

    /**
     * @return EmptyGifView
     **/
    public function render($model = null)
    {
        header('Content-Type: image/gif');
        header('Content-Length: 43');
        header('Accept-Ranges: none');
        // NOTE: this is hardcoded empty gif 1x1 image
        print 'GIF89a  �  ��� '.'  !�    ,   '.'    D ;';
        return $this;
    }
}