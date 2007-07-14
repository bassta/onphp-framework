<?php
/***************************************************************************
 *   Copyright (C) 2005-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * Rules support for final Form.
	 * 
	 * @ingroup Form
	**/
	abstract class RegulatedForm extends PlainForm
	{
		protected $rules		= array(); // forever
		protected $violated		= array(); // rules

		/**
		 * @throws WrongArgumentException
		 * @return Form
		**/
		public function addRule($name, LogicalObject $rule)
		{
			Assert::isString($name);
			
			$this->rules[$name] = $rule;
			
			return $this;
		}
		
		/**
		 * @throws MissingElementException
		 * @return Form
		**/
		public function dropRuleByName($name)
		{
			if (isset($this->rules[$name])) {
				unset($this->rules[$name]);
				return $this;
			}
			
			throw new MissingElementException(
				"no such rule with '{$name}' name"
			);
		}
		
		/**
		 * @return Form
		**/
		public function checkRules()
		{
			foreach ($this->rules as $name => $logicalObject) {
				if (!$logicalObject->toBoolean($this))
					$this->violated[$name] = Form::WRONG;
			}

			return $this;
		}
	}
?>