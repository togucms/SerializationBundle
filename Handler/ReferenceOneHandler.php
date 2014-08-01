<?php

/*
 * Copyright (c) 2012-2014 Alessandro Siragusa <alessandro@togu.io>
 *
 * This file is part of the Togu CMS.
 *
 * Togu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Togu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Togu.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Togu\SerializationBundle\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Context;

use Togu\AnnotationBundle\Data\AnnotationProcessor;
use Doctrine\ODM\PHPCR\ReferenceManyCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;


class ReferenceOneHandler implements SubscribingHandlerInterface {
	private $processor;
	private $managerRegistry;

	public function __construct(AnnotationProcessor $typeProcessor, ManagerRegistry $managerRegistry) {
		$this->processor = $typeProcessor;
		$this->managerRegistry = $managerRegistry;
	}

	public static function getSubscribingMethods()
	{
		return array(
			array(
				'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
				'format' => 'json',
				'type' => 'referenceone',
				'method' => 'serializeReference',
			),
			array(
				'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
				'format' => 'json',
				'type' => 'referenceone',
				'method' => 'deserializeReference',
			),
		);
	}

	public function deserializeReference(VisitorInterface $visitor, $id, array $type, Context $context) {
		$manager = $this->managerRegistry->getManager();

		return $manager->find(null, $id);
	}

	public function serializeReference(VisitorInterface $visitor, $document, array $type, Context $context)
	{
		return $document->getId();
/*		return array(
			"id" => $document->getId(),
			"type" => $this->processor->getModelOfClass($document)
		);
*/
	}

}