<?php
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
	final class OqlGrammarRuleWrapperParseStrategy extends OqlGrammarRuleParseStrategy
	{
		/**
		 * @return OqlGrammarRuleWrapperParseStrategy
		**/
		public static function me()
		{
			return Singleton::getInstance(__CLASS__);
		}
		
		public function parse(OqlGrammarRule $rule, OqlTokenizer $tokenizer)
		{
			Assert::isInstance($rule, 'OqlGrammarRuleWrapper');
			
			return $rule->getRule()->parse($rule, $tokenizer);
		}
	}
?>