<?php

namespace Skel\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

abstract class Controller
{
	protected function isRest(Request $request)
	{
		$expectedTypes = array('application/json', 'application/xml');
		return in_array($request->headers->get('accept'), $expectedTypes);
	}

	protected function getRestType(Request $request)
	{
		switch ($request->headers->get('accept')) {
			case 'application/json':
				$type = 'json';
				break;
			case 'application/xml':
				$type = 'xml';
				break;
		}
		return $type;
	}

	protected function serialize($data, $type)
	{
		$serializer = SerializerBuilder::create()->build();
		return $serializer->serialize($data, $type);
	}
}