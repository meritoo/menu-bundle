<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\MenuBundle\Domain\Html\Attributes;
use Meritoo\MenuBundle\Domain\Item;
use Meritoo\MenuBundle\Domain\Link;
use Meritoo\MenuBundle\Domain\Menu;

/**
 * Test case for the menu
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\MenuBundle\Domain\Menu
 */
class MenuTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Menu::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    public function testRenderWithoutItems(): void
    {
        $menu = new Menu([]);
        static::assertSame('', $menu->render(new Templates()));
    }

    /**
     * @param Templates $templates       Collection/storage of templates that will be required while rendering
     * @param string    $expectedMessage Expected message of exception
     *
     * @dataProvider provideIncompleteTemplates
     */
    public function testRenderWithoutTemplates(Templates $templates, string $expectedMessage): void
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $menu = new Menu([
            new Item(new Link('Test 1', '')),
            new Item(new Link('Test 2', '/')),
        ]);

        $menu->render($templates);
    }

    public function testRenderUsingLinksWithoutNames(): void
    {
        $menu = new Menu([
            new Item(new Link('', '')),
            new Item(new Link('', '/')),
        ]);

        static::assertSame('', $menu->render(new Templates()));
    }

    /**
     * @param string    $description Description of test
     * @param Templates $templates   Collection/storage of templates that will be required while rendering
     * @param Menu      $menu        Menu to render
     * @param string    $expected    Expected rendered menu
     *
     * @dataProvider provideTemplatesAndMenuToRender
     */
    public function testRender(string $description, Templates $templates, Menu $menu, string $expected): void
    {
        static::assertSame($expected, $menu->render($templates), $description);
    }

    public function testAddAttribute(): void
    {
        $menu = new Menu([
            new Item(new Link('Test 1', '')),
            new Item(new Link('Test 2', '/')),
        ]);

        $menu->addAttribute('id', 'test');
        $menu->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menu, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function testAddAttributes(): void
    {
        $menu = new Menu([
            new Item(new Link('Test 1', '')),
            new Item(new Link('Test 2', '/')),
        ]);

        $menu->addAttributes([
            'id' => 'test',
        ]);

        $menu->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menu, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function provideIncompleteTemplates(): ?\Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield[
            new Templates(),
            sprintf($template, Link::class),
        ];

        yield[
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
            ]),
            sprintf($template, Item::class),
        ];

        yield[
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
            ]),
            sprintf($template, Menu::class),
        ];

        yield[
            new Templates([
                Link::class      => new Template('<a href="%url%">%name%</a>'),
                Item::class      => new Template('<div class="item">%link%</div>'),
                \stdClass::class => new Template('<div class="container">%items%</div>'),
            ]),
            sprintf($template, Menu::class),
        ];
    }

    /**
     * @param string    $description Description of test
     * @param array     $links       An array of arrays (0-based indexes): [0] name of link, [1] url of link
     * @param Menu|null $expected    Expected Menu
     *
     * @dataProvider provideItemsToCreate
     */
    public function testCreate(string $description, array $links, ?Menu $expected): void
    {
        static::assertEquals($expected, Menu::create($links), $description);
    }

    /**
     * @param string     $description    Description of test
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of item
     * @param array|null $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @param Menu|null  $expected       Expected Menu
     *
     * @dataProvider provideItemsToCreateWithAttributes
     */
    public function testCreateWithAttributes(
        string $description,
        ?Menu $expected,
        array $links,
        ?array $menuAttributes = null
    ): void {
        static::assertEquals($expected, Menu::create($links, $menuAttributes), $description);
    }

    public function provideTemplatesAndMenuToRender(): ?\Generator
    {
        $items = [
            new Item(new Link('Test 1', '/test1')),
        ];

        $menu1 = new Menu($items);
        $menu1->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'container');

        $menu2 = new Menu($items);
        $menu2->addAttribute('id', 'main-menu');
        $menu2->addAttribute('data-position', '12');

        yield[
            'Menu with 1 item only',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
                Menu::class => new Template('<div class="container">%items%</div>'),
            ]),
            new Menu($items),
            '<div class="container">'
            . '<div class="item"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];

        yield[
            'Menu with 3 items',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
                Menu::class => new Template('<div class="container">%items%</div>'),
            ]),
            new Menu([
                new Item(new Link('Test 1', '/test1')),
                new Item(new Link('Test 2', '/test2')),
                new Item(new Link('Test 3', '/test3')),
            ]),
            '<div class="container">'
            . '<div class="item"><a href="/test1">Test 1</a></div>'
            . '<div class="item"><a href="/test2">Test 2</a></div>'
            . '<div class="item"><a href="/test3">Test 3</a></div>'
            . '</div>',
        ];

        yield[
            'Menu with 3 items and unordered list as template',
            new Templates([
                Link::class => new Template('<a href="%url%" class="blue">%name%</a>'),
                Item::class => new Template('<li class="item">%link%</li>'),
                Menu::class => new Template('<div class="container"><ul class="wrapper">%items%</ul></div>'),
            ]),
            new Menu([
                new Item(new Link('Test 1', '/test1')),
                new Item(new Link('Test 2', '/test2')),
                new Item(new Link('Test 3', '/test3')),
            ]),
            '<div class="container">'
            . '<ul class="wrapper">'
            . '<li class="item"><a href="/test1" class="blue">Test 1</a></li>'
            . '<li class="item"><a href="/test2" class="blue">Test 2</a></li>'
            . '<li class="item"><a href="/test3" class="blue">Test 3</a></li>'
            . '</ul>'
            . '</div>',
        ];

        yield[
            'With 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
                Menu::class => new Template('<div%attributes%>%items%</div>'),
            ]),
            $menu1,
            '<div class="container">'
            . '<div class="item"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];

        yield[
            'With more than 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
                Menu::class => new Template('<div%attributes%>%items%</div>'),
            ]),
            $menu2,
            '<div id="main-menu" data-position="12">'
            . '<div class="item"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];
    }

    public function provideItemsToCreate(): ?\Generator
    {
        yield[
            'An empty array',
            [],
            null,
        ];

        yield[
            'Item with empty strings',
            [
                [
                    '',
                    '',
                ],
            ],
            new Menu([new Item(new Link('', ''))]),
        ];

        yield[
            'Item with incorrect indexes',
            [
                [
                    'x' => 'Test 1',
                    'y' => '/test',
                ],
            ],
            new Menu([new Item(new Link('', ''))]),
        ];

        yield[
            'Item with not empty name and empty url',
            [
                [
                    'Test',
                    '',
                ],
            ],
            new Menu([new Item(new Link('Test', ''))]),
        ];

        yield[
            'Item with not empty name and not empty url',
            [
                [
                    'Test',
                    '/',
                ],
            ],
            new Menu([new Item(new Link('Test', '/'))]),
        ];

        yield[
            'More than 1 item',
            [
                [
                    'Test 1',
                    '/test',
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                ],
            ],
            new Menu([
                new Item(new Link('Test 1', '/test')),
                new Item(new Link('Test 2', '/test/2')),
                new Item(new Link('Test 3', '/test/46/test')),
            ]),
        ];
    }

    public function provideItemsToCreateWithAttributes(): ?\Generator
    {
        $link1Attributes = [
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-class',
        ];

        $link3Attributes = [
            'id'                            => 'test-test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-another-class',
        ];

        $item1Attributes = [
            'data-show'                     => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
        ];

        $item2Attributes = [
            'data-show'                     => 'test-test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-next-class',
        ];

        $item3Attributes = [
            'id'                            => 'test-test',
            'data-show'                     => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-last-class',
        ];

        $menu1Attributes = [
            'id'                            => 'main',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-menu',
        ];

        $menu2Attributes = [
            'id'                            => 'left-navigation',
            Attributes::ATTRIBUTE_CSS_CLASS => 'hide-xs',
        ];

        $link1WithAttributes = new Link('Test 1', '/test');
        $link1WithAttributes->addAttributes($link1Attributes);

        $link3WithAttributes = new Link('Test 3', '/test/46/test');
        $link3WithAttributes->addAttributes($link3Attributes);

        $item1WithAttributes = new Item($link1WithAttributes);
        $item1WithAttributes->addAttributes($item1Attributes);

        $item2WithAttributes = new Item(new Link('Test 2', '/test/2'));
        $item2WithAttributes->addAttributes($item2Attributes);

        $item3WithAttributes = new Item($link3WithAttributes);
        $item3WithAttributes->addAttributes($item3Attributes);

        $menu1WithAttributes = new Menu([
            new Item(new Link('Test 1', '/test')),
            new Item(new Link('Test 2', '/test/2')),
            new Item(new Link('Test 3', '/test/46/test')),
        ]);

        $menu2WithAttributes = new Menu([
            new Item($link1WithAttributes),
            new Item(new Link('Test 2', '/test/2')),
            new Item($link3WithAttributes),
        ]);

        $menu1WithAttributes->addAttributes($menu1Attributes);
        $menu2WithAttributes->addAttributes($menu2Attributes);

        yield[
            'Links with attributes',
            new Menu([
                new Item($link1WithAttributes),
                new Item(new Link('Test 2', '/test/2')),
                new Item($link3WithAttributes),
            ]),
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                ],
            ],
        ];

        yield[
            'Links and items with attributes',
            new Menu([
                $item1WithAttributes,
                $item2WithAttributes,
                $item3WithAttributes,
            ]),
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                    $item1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                    null,
                    $item2Attributes,
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                    $item3Attributes,
                ],
            ],
        ];

        yield[
            'Menu only with attributes',
            $menu1WithAttributes,
            [
                [
                    'Test 1',
                    '/test',
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                ],
            ],
            $menu1Attributes,
        ];

        yield[
            'Menu, links and items with attributes',
            $menu2WithAttributes,
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                ],
            ],
            $menu2Attributes,
        ];
    }
}
