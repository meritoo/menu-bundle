<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension related to menu
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MenuExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return array_merge(parent::getFunctions(), [
            new TwigFunction(
                'meritoo_menu_bar',
                [
                    MenuRuntime::class,
                    'renderMenuBar',
                ],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ]);
    }
}
