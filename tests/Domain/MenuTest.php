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
use Meritoo\Common\ValueObject\Template;
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
     * @param string    $description    Description of test
     * @param array     $linksNamesUrls Pairs of key-value where: key - name of link, value - url of link
     * @param Menu|null $expected       Expected Menu
     *
     * @dataProvider provideItemsToCreate
     */
    public function testCreate(string $description, array $linksNamesUrls, ?Menu $expected): void
    {
        static::assertEquals($expected, Menu::create($linksNamesUrls), $description);
    }

    public function provideTemplatesAndMenuToRender(): ?\Generator
    {
        yield[
            'Menu with 1 item only',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
                Menu::class => new Template('<div class="container">%items%</div>'),
            ]),
            new Menu([
                new Item(new Link('Test 1', '/test1')),
            ]),
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
    }

    public function provideItemsToCreate(): ?\Generator
    {
        yield[
            'An empty array',
            [],
            null,
        ];

        yield[
            '1 item only with empty strings',
            [
                '' => '',
            ],
            new Menu([new Item(new Link('', ''))]),
        ];

        yield[
            '1 item only with not empty name and empty url',
            [
                'Test' => '',
            ],
            new Menu([new Item(new Link('Test', ''))]),
        ];

        yield[
            'More than 1 item',
            [
                'Test 1' => '/test',
                'Test 2' => '/test/2',
                'Test 3' => '/test/46/test',
            ],
            new Menu([
                new Item(new Link('Test 1', '/test')),
                new Item(new Link('Test 2', '/test/2')),
                new Item(new Link('Test 3', '/test/46/test')),
            ]),
        ];
    }
}
