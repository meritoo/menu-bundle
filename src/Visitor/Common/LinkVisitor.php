<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Visitor\Common;

use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Common visitor of the Link menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class LinkVisitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        // Nothing to do
    }
}
