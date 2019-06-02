<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Visitor\Factory\Collection;

use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\MenuBundle\Visitor\Common\VisitorFactory;
use Meritoo\MenuBundle\Visitor\Factory\Collection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class VisitorFactoryCollectionTest
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\MenuBundle\Visitor\Factory\Collection
 */
class CollectionTest extends KernelTestCase
{
    use BaseTestCaseTrait;

    public function testConstructor(): void
    {
        static::assertHasNoConstructor(Collection::class);
    }

    public function testDefaultElementsCount(): void
    {
        /** @var Collection $collection */
        $collection = static::$container->get(Collection::class);

        static::assertEquals(1, $collection->count());
    }

    public function testAddVisitorFactory(): void
    {
        /** @var Collection $collection */
        $collection = static::$container->get(Collection::class);

        /** @var VisitorFactory $visitorFactory */
        $visitorFactory = static::$container->get(VisitorFactory::class);

        $collection->addVisitorFactory($visitorFactory);
        static::assertEquals(2, $collection->count());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
    }
}
