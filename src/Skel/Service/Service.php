<?php

namespace Skel\Service;

abstract class Service implements ServiceInterface
{
	protected $em;

    public function setEm($em)
    {
        $this->em = $em;
    }
}