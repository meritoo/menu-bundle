<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Twig;

use Generator;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Menu\Html\Attributes;
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

    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            MenuRuntime::class,
            OopVisibilityType::IS_PUBLIC,
            4,
            4
        );
    }

    public function testIsInstanceOfRuntimeExtensionInterface(): void
    {
        $runtime = static::$container->get(MenuRuntime::class);
        static::assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    /**
     * @param string     $description    Description of test
     * @param string     $expected       Expected rendered menu
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of link's container
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     *
     * @dataProvider provideDataToTenderMenuUsingDefaults
     */
    public function testRenderMenuUsingDefaults(
        string $description,
        string $expected,
        array $links,
        ?array $menuAttributes = null
    ): void {
        static::bootKernel([
            'environment' => 'defaults',
        ]);

        $menu = static::$container
            ->get(MenuRuntime::class)
            ->renderMenu($links, $menuAttributes)
        ;

        static::assertSame($expected, $menu, $description);
    }

    /**
     * @param string     $description    Description of test
     * @param string     $expected       Expected rendered menu
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of link's container
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     *
     * @dataProvider provideDataToTenderMenuUsingTestEnvironment
     */
    public function testRenderMenuUsingTestEnvironment(
        string $description,
        string $expected,
        array $links,
        ?array $menuAttributes = null
    ): void {
        $menu = static::$container
            ->get(MenuRuntime::class)
            ->renderMenu($links, $menuAttributes)
        ;

        static::assertSame($expected, $menu, $description);
    }

    public function provideDataToTenderMenuUsingDefaults(): ?Generator
    {
        yield[
            'An empty array',
            '',
            [],
        ];

        yield[
            '1 link\'s container only with empty strings',
            '',
            [
                [
                    '',
                    '',
                ],
            ],
        ];

        yield[
            '1 link\'s container only with not empty name and empty url',
            '<div>'
            . '<div>'
            . '' . '<a href="">Test</a>'
            . '</div>'
            . '</div>',
            [
                [
                    'Test',
                    '',
                ],
            ],
        ];

        yield[
            'More than 1 link\'s container',
            '<div>'
            . '<div>'
            . '' . '<a href="/test">Test 1</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/2">Test 2</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/46/test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            '<div>'
            . '<div>'
            . '' . '<a href="/test" id="main">Test 1</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/2" id="email" class="blue">Test 2</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a>'
            . '</div>'
            . '</div>',
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
            'With attributes of link\'s container',
            '<div>'
            . '<div data-show="test" class="my-big-class">'
            . '' . '<a href="/test">Test 1</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/2">Test 2</a>'
            . '</div>'
            . '<div id="test-test" data-show="true" class="my-last-class">'
            . '' . '<a href="/test/46/test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            '<div id="main" class="my-menu">'
            . '<div>'
            . '' . '<a href="/test">Test 1</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/2">Test 2</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/46/test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            'With attributes of all elements',
            '<div id="main" class="my-menu">'
            . '<div data-show="test" class="my-big-class">'
            . '' . '<a href="/test" id="main">Test 1</a>'
            . '</div>'
            . '<div>'
            . '' . '<a href="/test/2" id="email" class="blue">Test 2</a>'
            . '</div>'
            . '<div id="test-test" data-show="true" class="my-last-class">'
            . '' . '<a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a>'
            . '</div>'
            . '</div>',
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

    public function provideDataToTenderMenuUsingTestEnvironment(): ?Generator
    {
        yield[
            'An empty array',
            '',
            [],
        ];

        yield[
            '1 link\'s container only with empty strings',
            '',
            [
                [
                    '',
                    '',
                ],
            ],
        ];

        yield[
            '1 link\'s container only with not empty name and empty url',
            '<div data-env="test">'
            . '<div data-env="test">'
            . '' . '<a href="" data-env="test">Test</a>'
            . '</div>'
            . '</div>',
            [
                [
                    'Test',
                    '',
                ],
            ],
        ];

        yield[
            'More than 1 link\'s container',
            '<div data-env="test">'
            . '<div data-env="test">'
            . '' . '<a href="/test" data-env="test">Test 1</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/2" data-env="test">Test 2</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/46/test" data-env="test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            '<div data-env="test">'
            . '<div data-env="test">'
            . '' . '<a href="/test" id="main" data-env="test">Test 1</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/2" id="email" class="blue" data-env="test">Test 2</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/46/test" data-show="test" class="my-big-class" data-env="test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            'With attributes of links\' containers',
            '<div data-env="test">'
            . '<div data-show="test" class="my-big-class" data-env="test">'
            . '' . '<a href="/test" data-env="test">Test 1</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/2" data-env="test">Test 2</a>'
            . '</div>'
            . '<div id="test-test" data-show="true" class="my-last-class" data-env="test">'
            . '' . '<a href="/test/46/test" data-env="test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            '<div id="main" class="my-menu" data-env="test">'
            . '<div data-env="test">'
            . '' . '<a href="/test" data-env="test">Test 1</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/2" data-env="test">Test 2</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/46/test" data-env="test">Test 3</a>'
            . '</div>'
            . '</div>',
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
            'With attributes of all elements',
            '<div id="main" class="my-menu" data-env="test">'
            . '<div data-show="test" class="my-big-class" data-env="test"><a href="/test" id="main" data-env="test">Test 1</a>'
            . '</div>'
            . '<div data-env="test">'
            . '' . '<a href="/test/2" id="email" class="blue" data-env="test">Test 2</a>'
            . '</div>'
            . '<div id="test-test" data-show="true" class="my-last-class" data-env="test">'
            . '' . '<a href="/test/46/test" data-show="test" class="my-big-class" data-env="test">Test 3</a>'
            . '</div>'
            . '</div>',
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

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
    }
}
