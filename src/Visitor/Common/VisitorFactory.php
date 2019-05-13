<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Visitor\Common;

use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\Visitor\Factory\VisitorFactory as BaseVisitorFactory;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Common factory of visitors for each supported menu parts
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class VisitorFactory extends BaseVisitorFactory
{
    /**
     * Common visitor of the Link menu part
     *
     * @var LinkVisitor
     */
    private $linkVisitor;

    /**
     * Common visitor of the LinkContainer menu part
     *
     * @var LinkContainerVisitor
     */
    private $linkContainerVisitor;

    /**
     * Common visitor of the Menu menu part
     *
     * @var MenuVisitor
     */
    private $menuVisitor;

    /**
     * Class constructor
     *
     * @param LinkVisitor          $linkVisitor          Common visitor of the Link menu part
     * @param LinkContainerVisitor $linkContainerVisitor Common visitor of the LinkContainer menu part
     * @param MenuVisitor          $menuVisitor          Common visitor of the Menu menu part
     */
    public function __construct(
        LinkVisitor $linkVisitor,
        LinkContainerVisitor $linkContainerVisitor,
        MenuVisitor $menuVisitor
    ) {
        $this->linkVisitor = $linkVisitor;
        $this->linkContainerVisitor = $linkContainerVisitor;
        $this->menuVisitor = $menuVisitor;
    }

    /**
     * {@inheritdoc}
     */
    protected function createLinkContainerVisitor(LinkContainer $linkContainer): VisitorInterface
    {
        return $this->linkContainerVisitor;
    }

    /**
     * {@inheritdoc}
     */
    protected function createLinkVisitor(Link $link): VisitorInterface
    {
        return $this->linkVisitor;
    }

    /**
     * {@inheritdoc}
     */
    protected function createMenuVisitor(Menu $menu): VisitorInterface
    {
        return $this->menuVisitor;
    }
}
