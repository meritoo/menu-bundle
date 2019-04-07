<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Twig;

use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
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
        static::assertHasNoConstructor(MenuRuntime::class);
    }

    public function testIsInstanceOfRuntimeExtensionInterface(): void
    {
        $runtime = static::$container->get(MenuRuntime::class);
        static::assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    /**
     * @param string $description    Description of test
     * @param array  $linksNamesUrls Pairs of key-value where: key - name of link, value - url of link
     * @param string $expected       Expected rendered menu bar
     *
     * @dataProvider provideLinksNamesUrlsToRenderMenuBar
     */
    public function testRenderMenuBar(string $description, array $linksNamesUrls, string $expected): void
    {
        $menuBar = static::$container
            ->get(MenuRuntime::class)
            ->renderMenuBar($linksNamesUrls)
        ;

        static::assertSame($expected, $menuBar, $description);
    }

    public function provideLinksNamesUrlsToRenderMenuBar(): ?\Generator
    {
        yield[
            'An empty array',
            [],
            '',
        ];

        yield[
            '1 item only with empty strings',
            [
                '' => '',
            ],
            '',
        ];

        yield[
            '1 item only with not empty name and empty url',
            [
                'Test' => '',
            ],
            '<div class="container"><div class="item"><a href="">Test</a></div></div>',
        ];

        yield[
            'More than 1 item',
            [
                'Test 1' => '/test',
                'Test 2' => '/test/2',
                'Test 3' => '/test/46/test',
            ],
            '<div class="container"><div class="item"><a href="/test">Test 1</a></div><div class="item"><a href="/test/2">Test 2</a></div><div class="item"><a href="/test/46/test">Test 3</a></div></div>',
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
