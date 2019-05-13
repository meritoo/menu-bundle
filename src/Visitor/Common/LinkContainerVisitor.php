<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Visitor\Common;

use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Common visitor of the LinkContainer menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class LinkContainerVisitor implements VisitorInterface
{
    /**
     * CSS class of active/current link
     *
     * @var string
     */
    public const ACTIVE_LINK_CSS_CLASS = 'active';

    /**
     * Request stack that controls the lifecycle of requests
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Class constructor
     *
     * @param RequestStack $requestStack Request stack that controls the lifecycle of requests
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        // It's not a LinkContainer? Nothing to do
        if (!$menuPart instanceof LinkContainer) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        // No request? Nothing to do
        if (null === $request) {
            return;
        }

        $linkUrl = $menuPart->getLink()->getUrl();
        $requestedUrl = $request->getRequestUri();

        if ($linkUrl === $requestedUrl) {
            $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, static::ACTIVE_LINK_CSS_CLASS);
        }
    }
}
