<?php
/***************************************************************************
 *   Copyright (C) 2005-2006 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * System-V semaphores based locking.
	 * 
	 * @ingroup Lockers
	**/
	final class SystemFiveLocker extends BaseLocker
	{
		public function get($key)
		{
			try {
				if (!isset($this->pool[$key]))
					$this->pool[$key] = sem_get($key, 1, 0600, false);
				
				sem_acquire($this->pool[$key]);
				
				return $this->pool[$key];
			} catch (BaseException $e) {
				return null;
			}
			
			/* NOTREACHED */
		}
		
		public function free($key)
		{
			if (isset($this->pool[$key])) {
				try {
					return sem_release($this->pool[$key]);
				} catch (BaseException $e) {
					// acquired by another process
					return false;
				}
			}
			
			return null;
		}
		
		public function drop($key)
		{
			if (isset($this->pool[$key])) {
				try {
					return sem_remove($this->pool[$key]);
				} catch (BaseException $e) {
					unset($this->pool[$key]); // already race-removed
					return false;
				}
			}
			
			return null;
		}
	}
?>