<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Twig;

use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\MenuBundle\Domain\Html\Attributes;
use Meritoo\MenuBundle\Twig\MenuRuntime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Test case for the runtime class related to MenuExtension Twig Extension
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\MenuBundle\Twig\MenuRuntime
 */
class MenuRuntimeTest extends KernelTestCase
{
    use BaseTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
    }

    public function testConstructor(): void
    {
        static::assertHasNoConstructor(MenuRuntime::class);
    }

    public function testIsInstanceOfRuntimeExtensionInterface(): void
    {
        $runtime = static::$container->get(MenuRuntime::class);
        static::assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    /**
     * @param string     $description    Description of test
     * @param string     $expected       Expected rendered menu bar
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of item
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     *
     * @dataProvider provideLinksNamesUrlsToRenderMenuBar
     */
    public function testRenderMenuBar(
        string $description,
        string $expected,
        array $links,
        ?array $menuAttributes = null
    ): void {
        $menuBar = static::$container
            ->get(MenuRuntime::class)
            ->renderMenuBar($links, $menuAttributes)
        ;

        static::assertSame($expected, $menuBar, $description);
    }

    public function provideLinksNamesUrlsToRenderMenuBar(): ?\Generator
    {
        yield[
            'An empty array',
            '',
            [],
        ];

        yield[
            '1 item only with empty strings',
            '',
            [
                [
                    '',
                    '',
                ],
            ],
        ];

        yield[
            '1 item only with not empty name and empty url',
            '<div><div><a href="">Test</a></div></div>',
            [
                [
                    'Test',
                    '',
                ],
            ],
        ];

        yield[
            'More than 1 item',
            '<div><div><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div><a href="/test/46/test">Test 3</a></div></div>',
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
        ];

        yield[
            'With attributes of links',
            '<div><div><a href="/test" id="main">Test 1</a></div><div><a href="/test/2" id="email" class="blue">Test 2</a></div><div><a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a></div></div>',
            [
                [
                    'Test 1',
                    '/test',
                    [
                        'id' => 'main',
                    ],
                ],
                [
                    'Test 2',
                    '/test/2',
                    [
                        'id'                            => 'email',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'blue',
                    ],
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    [
                        'data-show'                     => 'test',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
                    ],
                ],
            ],
        ];

        yield[
            'With attributes of items',
            '<div><div data-show="test" class="my-big-class"><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class"><a href="/test/46/test">Test 3</a></div></div>',
            [
                [
                    'Test 1',
                    '/test',
                    null,
                    [
                        'data-show'                     => 'test',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
                    ],
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    null,
                    [
                        'id'                            => 'test-test',
                        'data-show'                     => 'true',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-last-class',
                    ],
                ],
            ],
        ];

        yield[
            'With attributes of menu',
            '<div id="main" class="my-menu"><div><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div><a href="/test/46/test">Test 3</a></div></div>',
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
            [
                'id'                            => 'main',
                Attributes::ATTRIBUTE_CSS_CLASS => 'my-menu',
            ],
        ];

        yield[
            'With attributes of links, items and menu',
            '<div id="main" class="my-menu"><div data-show="test" class="my-big-class"><a href="/test" id="main">Test 1</a></div><div><a href="/test/2" id="email" class="blue">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class"><a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a></div></div>',
            [
                [
                    'Test 1',
                    '/test',
                    [
                        'id' => 'main',
                    ],
                    [
                        'data-show'                     => 'test',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
                    ],
                ],
                [
                    'Test 2',
                    '/test/2',
                    [
                        'id'                            => 'email',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'blue',
                    ],
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    [
                        'data-show'                     => 'test',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
                    ],
                    [
                        'id'                            => 'test-test',
                        'data-show'                     => 'true',
                        Attributes::ATTRIBUTE_CSS_CLASS => 'my-last-class',
                    ],
                ],
            ],
            [
                'id'                            => 'main',
                Attributes::ATTRIBUTE_CSS_CLASS => 'my-menu',
            ],
        ];
    }
}
