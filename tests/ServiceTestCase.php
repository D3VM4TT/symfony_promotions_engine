<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceTestCase extends WebTestCase
{

    protected ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = static::createClient()->getContainer();
    }


}
