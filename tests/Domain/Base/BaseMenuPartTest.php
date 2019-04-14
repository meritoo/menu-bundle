<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain\Base;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\MenuBundle\Domain\Base\BaseMenuPart;
use Meritoo\MenuBundle\Domain\Html\Attributes;
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

    /**
     * @param string       $description Description of test
     * @param BaseMenuPart $menuPart    The part of menu
     * @param string       $name        Name of attribute
     * @param string       $value       Value of attribute
     * @param Attributes   $expected    Expected attributes
     *
     * @dataProvider provideAttributeToAdd
     */
    public function testAddAttribute(
        string $description,
        BaseMenuPart $menuPart,
        string $name,
        string $value,
        Attributes $expected
    ): void {
        $menuPart->addAttribute($name, $value);

        $attributes = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $attributes, $description);
    }

    public function testAddAttributeMoreThanOnce(): void
    {
        $menuPart = new MySecondMenuPart('100', 'blue');
        $menuPart->addAttribute('id', 'test');
        $menuPart->addAttribute('data-start', 'true');
        $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    /**
     * @param string       $description Description of test
     * @param BaseMenuPart $menuPart    The part of menu
     * @param array        $attributes  Key-value pairs, where key - name of attribute, value-value of attribute
     * @param Attributes   $expected    Expected attributes
     *
     * @dataProvider provideAttributesToAdd
     */
    public function testAddAttributes(
        string $description,
        BaseMenuPart $menuPart,
        array $attributes,
        Attributes $expected
    ): void {
        $menuPart->addAttributes($attributes);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing, $description);
    }

    public function testAddAttributesMoreThanOnce(): void
    {
        $menuPart = new MySecondMenuPart('100', 'blue');

        $menuPart->addAttributes([
            'id' => 'test',
        ]);

        $menuPart->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function provideMenuPartForRender(): ?Generator
    {
        $menuPart = new MyFirstMenuPart('Home');

        $menuPart->addAttributes([
            'id'                            => 'click-me',
            Attributes::ATTRIBUTE_CSS_CLASS => 'hide-sometimes',
        ]);

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

        yield[
            'Part of menu with attributes',
            $menuPart,
            new Templates([
                MyFirstMenuPart::class => new Template('<span%attributes%>%name%</span>'),
            ]),
            '<span id="click-me" class="hide-sometimes">Home</span>',
        ];
    }

    public function provideAttributeToAdd(): ?Generator
    {
        yield[
            '1st instance',
            new MyFirstMenuPart('Home'),
            'id',
            'test',
            new Attributes(['id' => 'test']),
        ];

        yield[
            '2nd instance',
            new MyFirstMenuPart('Home'),
            Attributes::ATTRIBUTE_CSS_CLASS,
            'blue-box',
            new Attributes([Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box']),
        ];
    }

    public function provideAttributesToAdd(): ?Generator
    {
        yield[
            '1st instance',
            new MyFirstMenuPart('Home'),
            [
                'id' => 'test',
            ],
            new Attributes([
                'id' => 'test',
            ]),
        ];

        yield[
            '2nd instance',
            new MySecondMenuPart('100', 'blue'),
            [
                'id'                            => 'test',
                'data-start'                    => 'true',
                Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
            ],
            new Attributes([
                'id'                            => 'test',
                'data-start'                    => 'true',
                Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
            ]),
        ];
    }
}
