<?php

namespace Skel\Service;

abstract class Service implements ServiceInterface
{
	protected $em;

	protected $cache;

    public function setEm($em)
    {
        $this->em = $em;
    }

    public function setCache($cache)
	{
		$this->cache = $cache;
	}

}