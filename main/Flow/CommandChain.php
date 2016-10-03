<?php

namespace onPHP\main\Flow;

use onPHP\core\Base\Assert;
use onPHP\core\Base\Prototyped;
use onPHP\core\Exceptions\BaseException;
use onPHP\core\Form\Form;

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
 * @ingroup Flow
 **/
final class CommandChain implements EditorCommand
{
    private $chain = array();

    /**
     * @return CommandChain
     **/
    public static function create()
    {
        return new self();
    }

    /**
     * @return CommandChain
     **/
    public function add(EditorCommand $command)
    {
        $this->chain[] = $command;
        return $this;
    }

    /**
     * @throws BaseException
     * @return ModelAndView
     **/
    public function run(Prototyped $subject, Form $form, HttpRequest $request)
    {
        Assert::isTrue(($size = count($this->chain)) > 0, 'command chain is empty');
        for ($i = 0; $i < $size; ++$i) {
            $command =& $this->chain[$i];
            try {
                $mav = $command->run($subject, $form, $request);
                if ($mav->getView() == EditorController::COMMAND_FAILED) {
                    $this->rollback($i);
                    return $mav;
                }
            } catch (BaseException $e) {
                $this->rollback($i);
                throw $e;
            }
        }
        return $mav;
    }

    /**
     * @return CommandChain
     **/
    private function rollback($position)
    {
        for ($i = $position; $i > -1; --$i) {
            if ($this->chain[$i] instanceof CarefulCommand) {
                try {
                    $this->chain[$i]->rollback();
                } catch (BaseException $e) {
                }
            }
        }
        return $this;
    }

    /**
     * @return CommandChain
     **/
    private function commit()
    {
        for ($size = count($this->chain), $i = 0; $i < $size; --$i) {
            if ($this->chain[$i] instanceof CarefulCommand) {
                $this->chain[$i]->commit();
            }
        }
        return $this;
    }
}