<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Visitor\Factory;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Meritoo\Common\Traits\Collection\ArrayAccessTrait;
use Meritoo\Common\Traits\Collection\CountableTrait;
use Meritoo\Common\Traits\Collection\GetTrait;
use Meritoo\Common\Traits\Collection\IteratorAggregateTrait;
use Meritoo\Common\Traits\Collection\MainTrait;
use Meritoo\Common\Traits\Collection\ModifyTrait;
use Meritoo\Common\Traits\Collection\VerifyTrait;
use Meritoo\Menu\Visitor\Factory\VisitorFactoryInterface;

/**
 * Collection of visitors' factories
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Collection implements Countable, ArrayAccess, IteratorAggregate
{
    use MainTrait;
    use ModifyTrait;
    use GetTrait;
    use VerifyTrait;
    use CountableTrait;
    use ArrayAccessTrait;
    use IteratorAggregateTrait;

    /**
     * Adds visitor factory to this collection
     *
     * @param VisitorFactoryInterface $visitorFactory The factory to add
     */
    public function addVisitorFactory(VisitorFactoryInterface $visitorFactory): void
    {
        if (null === $this->elements) {
            $this->elements = [];
        }

        $this->elements[] = $visitorFactory;
    }
}
