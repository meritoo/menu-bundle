<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\MenuBundle\Domain\Html\Attributes;
use Meritoo\MenuBundle\Domain\Item;
use Meritoo\MenuBundle\Domain\Link;
use stdClass;

/**
 * Test case for the item of menu
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\MenuBundle\Domain\Item
 */
class ItemTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Item::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
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

        $item = new Item(new Link('Test', '/'));
        $item->render($templates);
    }

    public function testRenderWithoutTemplate(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $item = new Item(new Link('Test', ''));
        $item->render(new Templates());
    }

    public function testRenderUsingLinkWithoutName(): void
    {
        $item = new Item(new Link('', '/'));
        static::assertSame('', $item->render(new Templates()));
    }

    public function testAddAttribute(): void
    {
        $item = new Item(new Link('', '/'));
        $item->addAttribute('id', 'test');
        $item->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($item, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function testAddAttributes(): void
    {
        $item = new Item(new Link('', '/'));

        $item->addAttributes([
            'id' => 'test',
        ]);

        $item->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($item, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function provideIncompleteTemplates(): ?Generator
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
                Link::class     => new Template('<a href="%url%">%name%</a>'),
                stdClass::class => new Template('<div class="container">%items%</div>'),
            ]),
            sprintf($template, Item::class),
        ];
    }

    /**
     * @param string    $description Description of test
     * @param Templates $templates   Collection/storage of templates that will be required while rendering
     * @param Item      $item        Item to render
     * @param string    $expected    Expected rendered item
     *
     * @dataProvider provideTemplatesAndItemToRender
     */
    public function testRender(string $description, Templates $templates, Item $item, string $expected): void
    {
        static::assertSame($expected, $item->render($templates), $description);
    }

    public function provideTemplatesAndItemToRender(): ?Generator
    {
        $link = new Link('Test', '/');

        $item1 = new Item($link);
        $item1->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue');

        $item2 = new Item($link);
        $item2->addAttribute('id', 'main-item');
        $item2->addAttribute('data-position', '12');

        yield[
            'Simple template',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
                Item::class => new Template('<div class="item">%link%</div>'),
            ]),
            new Item(new Link('Test', '/')),
            '<div class="item"><a href="/">Test</a></div>',
        ];

        yield[
            'Complex template',
            new Templates([
                Link::class => new Template('<a href="%url%" class="blue" data-title="test">%name%</a>'),
                Item::class => new Template('<div class="item" data-placement="top">%link%</div>'),
            ]),
            new Item(new Link('Test', '/')),
            '<div class="item" data-placement="top"><a href="/" class="blue" data-title="test">Test</a></div>',
        ];

        yield[
            'With 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
                Item::class => new Template('<div%attributes%>%link%</div>'),
            ]),
            $item1,
            '<div class="blue"><a href="/">Test</a></div>',
        ];

        yield[
            'With more than 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
                Item::class => new Template('<div%attributes%>%link%</div>'),
            ]),
            $item2,
            '<div id="main-item" data-position="12"><a href="/">Test</a></div>',
        ];
    }

    /**
     * @param string     $description    Description of test
     * @param Item       $expected       Expected Item
     * @param string     $linkName       Name of item's link
     * @param string     $linkUrl        Url of item's link
     * @param null|array $linkAttributes (optional) Attributes of item's link. Default: null (not provided).
     * @param null|array $itemAttributes (optional) Attributes of the item. Default: null (not provided).
     *
     * @dataProvider provideDataToCreate
     */
    public function testCreate(
        string $description,
        Item $expected,
        string $linkName,
        string $linkUrl,
        ?array $linkAttributes = null,
        ?array $itemAttributes = null
    ): void {
        $item = Item::create($linkName, $linkUrl, $linkAttributes, $itemAttributes);
        static::assertEquals($expected, $item, $description);
    }

    public function provideDataToCreate(): ?Generator
    {
        $linkAttributes = [
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-class',
        ];

        $itemAttributes = [
            'data-show'                     => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
        ];

        $linkWithAttributes = new Link('Test', '/test');
        $linkWithAttributes->addAttributes($linkAttributes);

        $itemWithAttributes = new Item(new Link('Test', '/test'));
        $itemWithAttributes->addAttributes($itemAttributes);

        $itemWithAttributesAndLinkWithAttributes = new Item($linkWithAttributes);
        $itemWithAttributesAndLinkWithAttributes->addAttributes($itemAttributes);

        yield[
            'An empty name and url of link',
            new Item(new Link('', '')),
            '',
            '',
        ];

        yield[
            'Not empty name and empty url of link',
            new Item(new Link('Test', '')),
            'Test',
            '',
        ];

        yield[
            'An empty name and not empty url of link',
            new Item(new Link('', 'Test')),
            '',
            'Test',
        ];

        yield[
            'Not empty name and not empty url of link',
            new Item(new Link('Test', '/test')),
            'Test',
            '/test',
        ];

        yield[
            'Link with attributes',
            new Item($linkWithAttributes),
            'Test',
            '/test',
            $linkAttributes,
        ];

        yield[
            'Item with attributes',
            $itemWithAttributes,
            'Test',
            '/test',
            null,
            $itemAttributes,
        ];

        yield[
            'Link and item with attributes',
            $itemWithAttributesAndLinkWithAttributes,
            'Test',
            '/test',
            $linkAttributes,
            $itemAttributes,
        ];
    }
}
