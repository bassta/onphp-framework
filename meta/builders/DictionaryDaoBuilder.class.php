<?php
/***************************************************************************
 *   Copyright (C) 2006 by Konstantin V. Arkhipov                          *
 *   voxus@onphp.org                                                       *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	final class DictionaryDaoBuilder extends BaseBuilder
	{
		public static function build(MetaClass $class)
		{
			$out = self::getHead();
			
			$out .= <<<EOT
	abstract class Auto{$class->getName()}DAO extends MappedStorableDAO
	{
		protected \$mapping = array(

EOT;

			$tabs = "\t\t\t";
			
			$mapping = array();
						
			foreach ($class->getProperties() as $property) {
				
				$row = $tabs;
				
				if ($property->getType()->isGeneric()) {
					
					if ($property->getName() == $property->getDumbName())
						$map = 'null';
					else
						$map = $property->getDumbName();
					
					$row .= "'{$property->getName()}' => '{$map}'";
					
				} else {
					
					$remoteClass =
						MetaConfiguration::me()->
						getClassByName(
							$property->getType()->getClass()
						);
					
					$identifier = $remoteClass->getIdentifier();
					
					$row .=
						"'{$property->getName()}".ucfirst($identifier->getName())
						."' => '{$remoteClass->getDumbName()}_"
						."{$identifier->getDumbName()}'";
				}
				
				$mapping[] = $row;
			}
			
			$out .= implode(",\n", $mapping);
			
			$className = $class->getName();
			$varName = strtolower($className[0]).substr($className, 1);
			
			$out .= <<<EOT

		);
		
		public function getTable()
		{
			return '{$class->getDumbName()}';
		}
		
		public function getObjectName()
		{
			return '{$class->getName()}';
		}
		
		public function getSequence()
		{
			return '{$class->getDumbName()}_id';
		}
		
		public function setQueryFields(InsertOrUpdateQuery \$query, {$className} \${$varName})
		{
			return
				\$query->

EOT;
			
			$setters = array();
			
			$standaloneFillers = array();
			$chainFillers = array();
			
			foreach ($class->getProperties() as $property) {
				$setters[] = $property->toDaoField($className);
				
				$filler = $property->toDaoSetter($className);
				
				if (
					!$property->getType()->isGeneric()
					&& !$property->isRequired()
				)
					$standaloneFillers[] =
						$tabs
						.implode(
							"\n{$tabs}",
							explode("\n", $filler)
						);
				else
					$chainFillers[] =
						"{$tabs}\t"
						.implode(
							"\n{$tabs}\t",
							explode("\n", $filler)
						);
			}
			
			$out .= implode("->\n", $setters).";\n";

			$out .= <<<EOT
		}
		
		public function makeObject(&\$array, \$prefix = null)
		{
			\${$varName} = new {$className}();


EOT;

			if ($chainFillers) {
				
				$out .= "{$tabs}\${$varName}->\n";
				
				$out .= implode("->\n", $chainFillers).";\n\n";
			}
			
			if ($standaloneFillers) {
				
				$out .= implode("->\n", $standaloneFillers)."\n";
			}

			$out .=
				"{$tabs}return \${$varName};\n"
				."\t\t}\n"
				."\t}\n"
				.self::getHeel();
			
			return $out;
		}
	}
?>