<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Twig;

use Generator;
use Meritoo\CommonBundle\Test\Twig\Base\BaseTwigExtensionTestCase;
use Meritoo\MenuBundle\Twig\MenuExtension;

/**
 * Test case for the twig extension related to menu
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\MenuBundle\Twig\MenuExtension
 */
class MenuExtensionTest extends BaseTwigExtensionTestCase
{
    public function testGetFunctions(): void
    {
        $functions = static::$container
            ->get($this->getExtensionNamespace())
            ->getFunctions()
        ;

        static::assertCount(1, $functions);
    }

    /**
     * @param string $name       Name of the rendered template (used internally only)
     * @param string $sourceCode Source code of the rendered template
     * @param string $expected   Expected result of rendering
     *
     * @dataProvider provideTemplateToRenderMenuBarUsingTestEnvironment
     */
    public function testRenderMenuBarUsingTestEnvironment(string $name, string $sourceCode, string $expected): void
    {
        $this->verifyRenderedTemplate($name, $sourceCode, $expected);
    }

    /**
     * @param string $name       Name of the rendered template (used internally only)
     * @param string $sourceCode Source code of the rendered template
     * @param string $expected   Expected result of rendering
     *
     * @dataProvider provideTemplateToRenderMenuBarUsingDefaults
     */
    public function testRenderMenuBarUsingDefaults(string $name, string $sourceCode, string $expected): void
    {
        static::bootKernel([
            'environment' => 'defaults',
        ]);

        $this->verifyRenderedTemplate($name, $sourceCode, $expected);
    }

    public function provideTemplateToRenderMenuBarUsingDefaults(): ?Generator
    {
        yield[
            'without-items',
            '{{ meritoo_menu_bar({}) }}',
            '',
        ];

        yield[
            '1-item-only-with-empty-strings',
            '{{
                meritoo_menu_bar([
                    [
                        \'\',
                        \'\'
                    ]
                ])
            }}',
            '',
        ];

        yield[
            '1-item-only-with-not-empty-name-and-empty-url',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test1\',
                        \'\'
                    ]
                ])
            }}',
            '<div><div><a href="">Test1</a></div></div>',
        ];

        yield[
            'more-than-1-item',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\'
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\'
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\'
                    ]
                ])
            }}',
            '<div><div><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div><a href="/test/46/test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-links',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\',
                        {
                            \'id\': \'main\'
                        }
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\',
                        {
                            \'id\': \'email\',
                            \'class\': \'blue\'
                        }
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\',
                        {
                            \'data-show\': \'test\',
                            \'class\': \'my-big-class\'
                        }
                    ]
                ])
            }}',
            '<div><div><a href="/test" id="main">Test 1</a></div><div><a href="/test/2" id="email" class="blue">Test 2</a></div><div><a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-items',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\',
                        null,
                        {
                            \'data-show\': \'test\',
                            \'class\': \'my-big-class\'
                        }
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\'
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\',
                        null,
                        {
                            \'id\': \'test-test\',
                            \'data-show\': \'true\',
                            \'class\': \'my-last-class\'
                        }
                    ]
                ])
            }}',
            '<div><div data-show="test" class="my-big-class"><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class"><a href="/test/46/test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-menu',
            '{{
                meritoo_menu_bar(
                    [
                        [
                            \'Test 1\',
                            \'/test\'
                        ],
                        [
                            \'Test 2\',
                            \'/test/2\'
                        ],
                        [
                            \'Test 3\',
                            \'/test/46/test\'
                        ]
                    ],
                    {
                        \'id\': \'main\',
                        \'class\': \'my-menu\'
                    }
                )
            }}',
            '<div id="main" class="my-menu"><div><a href="/test">Test 1</a></div><div><a href="/test/2">Test 2</a></div><div><a href="/test/46/test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-links-items-and-menu',
            '{{
                meritoo_menu_bar(
                    [
                        [
                            \'Test 1\',
                            \'/test\',
                            {
                                \'id\': \'main\'
                            },
                            {
                                \'data-show\': \'test\',
                                \'class\': \'my-big-class\'
                            }
                        ],
                        [
                            \'Test 2\',
                            \'/test/2\',
                            {
                                \'id\': \'email\',
                                \'class\': \'blue\'
                            }
                        ],
                        [
                            \'Test 3\',
                            \'/test/46/test\',
                            {
                                \'data-show\': \'test\',
                                \'class\': \'my-big-class\'
                            },
                            {
                                \'id\': \'test-test\',
                                \'data-show\': \'true\',
                                \'class\': \'my-last-class\'
                            }
                        ]
                    ],
                    {
                        \'id\': \'main\',
                        \'class\': \'my-menu\'
                    }
                )
            }}',
            '<div id="main" class="my-menu"><div data-show="test" class="my-big-class"><a href="/test" id="main">Test 1</a></div><div><a href="/test/2" id="email" class="blue">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class"><a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a></div></div>',
        ];
    }

    public function provideTemplateToRenderMenuBarUsingTestEnvironment(): ?Generator
    {
        yield[
            'without-items',
            '{{ meritoo_menu_bar({}) }}',
            '',
        ];

        yield[
            '1-item-only-with-empty-strings',
            '{{
                meritoo_menu_bar([
                    [
                        \'\',
                        \'\'
                    ]
                ])
            }}',
            '',
        ];

        yield[
            '1-item-only-with-not-empty-name-and-empty-url',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test1\',
                        \'\'
                    ]
                ])
            }}',
            '<div data-env="test"><div data-env="test"><a href="" data-env="test">Test1</a></div></div>',
        ];

        yield[
            'more-than-1-item',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\'
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\'
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\'
                    ]
                ])
            }}',
            '<div data-env="test"><div data-env="test"><a href="/test" data-env="test">Test 1</a></div><div data-env="test"><a href="/test/2" data-env="test">Test 2</a></div><div data-env="test"><a href="/test/46/test" data-env="test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-links',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\',
                        {
                            \'id\': \'main\'
                        }
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\',
                        {
                            \'id\': \'email\',
                            \'class\': \'blue\'
                        }
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\',
                        {
                            \'data-show\': \'test\',
                            \'class\': \'my-big-class\'
                        }
                    ]
                ])
            }}',
            '<div data-env="test"><div data-env="test"><a href="/test" id="main" data-env="test">Test 1</a></div><div data-env="test"><a href="/test/2" id="email" class="blue" data-env="test">Test 2</a></div><div data-env="test"><a href="/test/46/test" data-show="test" class="my-big-class" data-env="test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-items',
            '{{
                meritoo_menu_bar([
                    [
                        \'Test 1\',
                        \'/test\',
                        null,
                        {
                            \'data-show\': \'test\',
                            \'class\': \'my-big-class\'
                        }
                    ],
                    [
                        \'Test 2\',
                        \'/test/2\'
                    ],
                    [
                        \'Test 3\',
                        \'/test/46/test\',
                        null,
                        {
                            \'id\': \'test-test\',
                            \'data-show\': \'true\',
                            \'class\': \'my-last-class\'
                        }
                    ]
                ])
            }}',
            '<div data-env="test"><div data-show="test" class="my-big-class" data-env="test"><a href="/test" data-env="test">Test 1</a></div><div data-env="test"><a href="/test/2" data-env="test">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class" data-env="test"><a href="/test/46/test" data-env="test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-menu',
            '{{
                meritoo_menu_bar(
                    [
                        [
                            \'Test 1\',
                            \'/test\'
                        ],
                        [
                            \'Test 2\',
                            \'/test/2\'
                        ],
                        [
                            \'Test 3\',
                            \'/test/46/test\'
                        ]
                    ],
                    {
                        \'id\': \'main\',
                        \'class\': \'my-menu\'
                    }
                )
            }}',
            '<div id="main" class="my-menu" data-env="test"><div data-env="test"><a href="/test" data-env="test">Test 1</a></div><div data-env="test"><a href="/test/2" data-env="test">Test 2</a></div><div data-env="test"><a href="/test/46/test" data-env="test">Test 3</a></div></div>',
        ];

        yield[
            'with-attributes-of-links-items-and-menu',
            '{{
                meritoo_menu_bar(
                    [
                        [
                            \'Test 1\',
                            \'/test\',
                            {
                                \'id\': \'main\'
                            },
                            {
                                \'data-show\': \'test\',
                                \'class\': \'my-big-class\'
                            }
                        ],
                        [
                            \'Test 2\',
                            \'/test/2\',
                            {
                                \'id\': \'email\',
                                \'class\': \'blue\'
                            }
                        ],
                        [
                            \'Test 3\',
                            \'/test/46/test\',
                            {
                                \'data-show\': \'test\',
                                \'class\': \'my-big-class\'
                            },
                            {
                                \'id\': \'test-test\',
                                \'data-show\': \'true\',
                                \'class\': \'my-last-class\'
                            }
                        ]
                    ],
                    {
                        \'id\': \'main\',
                        \'class\': \'my-menu\'
                    }
                )
            }}',
            '<div id="main" class="my-menu" data-env="test"><div data-show="test" class="my-big-class" data-env="test"><a href="/test" id="main" data-env="test">Test 1</a></div><div data-env="test"><a href="/test/2" id="email" class="blue" data-env="test">Test 2</a></div><div id="test-test" data-show="true" class="my-last-class" data-env="test"><a href="/test/46/test" data-show="test" class="my-big-class" data-env="test">Test 3</a></div></div>',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensionNamespace(): string
    {
        return MenuExtension::class;
    }
}
