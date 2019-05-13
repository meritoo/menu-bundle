<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Visitor\Factory;

use Meritoo\CommonBundle\Service\Base\BaseService;
use Meritoo\Menu\Menu;
use Meritoo\Menu\Visitor\Visitor;

/**
 * Service for the VisitorFactory
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Service extends BaseService
{
    /**
     * @var Collection
     */
    private $factoryCollection;

    /**
     * Class constructor
     *
     * @param Collection $factoryCollection Collection of visitors' factories
     */
    public function __construct(Collection $factoryCollection)
    {
        $this->factoryCollection = $factoryCollection;
    }

    /**
     * Runs visitor on menu and links
     *
     * @param Menu $menu The menu to visit
     */
    public function visitMenuAndLinks(Menu $menu): void
    {
        // No factories? Nothing to do
        if ($this->factoryCollection->isEmpty()) {
            return;
        }

        $menuParts = $menu->getAllMenuParts();

        // No menu parts? Nothing to do
        if (empty($menuParts)) {
            return;
        }

        foreach ($this->factoryCollection as $visitorFactory) {
            $visitor = new Visitor($visitorFactory);

            foreach ($menuParts as $menuPart) {
                $menuPart->accept($visitor);
            }
        }
    }
}
