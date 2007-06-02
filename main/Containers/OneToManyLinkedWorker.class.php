<?php
/***************************************************************************
 *   Copyright (C) 2005 by Konstantin V. Arkhipov                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Containers
	**/
	abstract class OneToManyLinkedWorker extends UnifiedContainerWorker
	{
		protected function targetize(SelectQuery $query)
		{
			return
				$query->andWhere(
					Expression::eqId(
						new DBField($this->container->getParentIdField()),
						$this->container->getParentObject()
					)
				);
		}
	}
?>