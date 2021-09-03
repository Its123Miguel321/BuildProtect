<?php

namespace Its123Miguel321\BuildProtect\API;

use Its123Miguel321\BuildProtect\Area;
use Its123Miguel321\BuildProtect\BuildProtect;

class AreasAPI
{
	/** @var BuildProtect $main */
	public $main;
	/** @var EventListener $events */
	public $events
	
	/**
	 * @param BuildProtect $main
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
	}
	
		
}
