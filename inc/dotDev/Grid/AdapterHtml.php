<?php
/**
 * Copyright (C) 2009 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of JDA Building Manager
 *
 * JDA Building Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * JDA Building Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with JDA Building Manager.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

class dotDev_Grid_AdapterHtml extends dotDev_Grid
{ 
	var $totalPages;
	var $currentPage;

	public function fetchArgsFromRequest()
	{
		//TODO: Implementar
	}
	
	public function fecthData()
	{
		parent::fecthData(); 

		$this->totalPages =  intval($this->totalRows / $this->pageSize);
		if ($this->totalRows > ($this->totalPages * $this->pageSize) )
		{
			$this->totalPages++;
		}
		
		$this->currentPage = 1;
		if($this->startRowIndex > 0)
		{
			$this->currentPage = ($this->startRowIndex / $this->pageSize) + 1;
		}
	}
}