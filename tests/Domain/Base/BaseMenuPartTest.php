<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain\Base;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\ValueObject\Template;
use Meritoo\MenuBundle\Domain\Base\BaseMenuPart;
use Meritoo\Test\MenuBundle\Domain\Base\BaseMenuPart\MyFirstMenuPart;
use Meritoo\Test\MenuBundle\Domain\Base\BaseMenuPart\MySecondMenuPart;

/**
 * Test case for the part of menu, e.g. link, item
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class BaseMenuPartTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertHasNoConstructor(BaseMenuPart::class);
    }

    /**
     * @param string       $description Description of test
     * @param BaseMenuPart $menuPart    The part of menu
     * @param Templates    $templates   Collection/storage of templates that will be required while rendering this and
     *                                  related objects, e.g. children of this object
     * @param string       $expected    Expected result of rendering
     *
     * @dataProvider provideMenuPartForRender
     */
    public function testRender(string $description, BaseMenuPart $menuPart, Templates $templates, string $expected): void
    {
        static::assertSame($expected, $menuPart->render($templates), $description);
    }

    public function provideMenuPartForRender(): ?\Generator
    {
        yield[
            'First part of menu - Home',
            new MyFirstMenuPart('Home'),
            new Templates([
                MyFirstMenuPart::class => new Template('<span>%name%</span>'),
            ]),
            '<span>Home</span>',
        ];

        yield[
            'First part of menu - Offer',
            new MyFirstMenuPart('Offer'),
            new Templates([
                MyFirstMenuPart::class => new Template('<div>%name%</div>'),
            ]),
            '<div>Offer</div>',
        ];

        yield[
            'Second part of menu - 100g',
            new MySecondMenuPart('100g', 'white'),
            new Templates([
                MySecondMenuPart::class => new Template('<span>%weight% and %color%</span>'),
            ]),
            '<span>100g and white</span>',
        ];

        yield[
            'Second part of menu - 1t',
            new MySecondMenuPart('1t', 'black'),
            new Templates([
                MySecondMenuPart::class => new Template('<div>%weight% and %color%</div>'),
            ]),
            '<div>1t and black</div>',
        ];
    }
}
