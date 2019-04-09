<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain\Base\BaseMenuPart;

use Meritoo\Common\Collection\Templates;
use Meritoo\MenuBundle\Domain\Base\BaseMenuPart;

/**
 * Part of menu used by test case of BaseMenuPart
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MySecondMenuPart extends BaseMenuPart
{
    /**
     * @var string
     */
    private $weight;

    /**
     * @var string
     */
    private $color;

    /**
     * Class constructor
     *
     * @param string $weight
     * @param string $color
     */
    public function __construct(string $weight, string $color)
    {
        $this->weight = $weight;
        $this->color = $color;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        return [
            'weight' => $this->weight,
            'color'  => $this->color,
        ];
    }
}
