<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\MenuBundle\Domain\Html;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\MenuBundle\Domain\Html\Attributes;

/**
 * Test case for the attributes of HTML element
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class AttributesTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Attributes::class,
            OopVisibilityType::IS_PUBLIC,
            1
        );
    }

    /**
     * @param string     $description   Description of test case
     * @param Attributes $attributes    The attributes collection
     * @param array      $newAttributes New attributes to add
     * @param int        $expectedCount Expected count of attributes after add
     *
     * @dataProvider provideAttributesToAddMultiple
     */
    public function testAddMultiple(
        string $description,
        Attributes $attributes,
        array $newAttributes,
        int $expectedCount
    ): void {
        $attributes->addMultiple($newAttributes);
        static::assertSame($expectedCount, $attributes->count(), $description);

        if (!empty($newAttributes)) {
            $firstName = Arrays::getFirstKey($newAttributes);
            $firstValue = $newAttributes[$firstName];

            $lastName = Arrays::getLastKey($newAttributes);
            $lastValue = $newAttributes[$lastName];

            static::assertSame($firstValue, $attributes[$firstName]);
            static::assertSame($lastValue, $attributes[$lastName]);
        }
    }

    /**
     * @param string     $description Description of test case
     * @param Attributes $attributes  The attributes collection
     * @param string     $expected    Expected attributes represented as string
     *
     * @dataProvider provideAttributesAsString
     */
    public function testAsString(string $description, Attributes $attributes, string $expected): void
    {
        static::assertSame($expected, $attributes->asString(), $description);
    }

    /**
     * @param string     $description Description of test case
     * @param Attributes $attributes  The attributes collection
     * @param string     $expected    Expected attributes represented as string
     *
     * @dataProvider provideAttributesAsString
     */
    public function testToString(string $description, Attributes $attributes, string $expected): void
    {
        static::assertSame($expected, (string)$attributes, $description);
    }

    public function provideAttributesAsString(): ?Generator
    {
        yield[
            'An empty instance (without any attribute)',
            new Attributes(),
            '',
        ];

        yield[
            'Attributes with empty string as value and name',
            new Attributes([
                'test-1' => null,
                'test-2' => '',
                ''       => '',
                'test-3' => 3,
            ]),
            'test-1="" test-2="" test-3="3"',
        ];

        yield[
            'Standard, regular attributes',
            new Attributes([
                'test-1' => 1,
                'test-2' => 2,
                'test-3' => 3,
            ]),
            'test-1="1" test-2="2" test-3="3"',
        ];

        yield[
            'Attributes with space used in name and in value',
            new Attributes([
                'test 1' => 1,
                'test 2' => '2 2',
                ''       => 'aaa',
                'test 3' => '3 by 3',
            ]),
            'test 1="1" test 2="2 2" test 3="3 by 3"',
        ];
    }

    public function provideAttributesToAddMultiple(): ?Generator
    {
        yield[
            'An empty instance & no new attributes',
            new Attributes(),
            [],
            0,
        ];

        yield[
            'An empty instance & new attributes with empty string as value and name',
            new Attributes(),
            [
                'test-1' => null,
                'test-2' => '',
                ''       => '',
                'test-3' => 3,
            ],
            3,
        ];

        yield[
            'An empty instance & standard, regular new attributes',
            new Attributes(),
            [
                'test-1' => 1,
                'test-2' => 2,
                'test-3' => 3,
            ],
            3,
        ];

        yield[
            'An empty instance & new attributes with space used in name and in value',
            new Attributes(),
            [
                'test 1' => 1,
                'test 2' => '2 2',
                ''       => 'aaa',
                'test 3' => '3 by 3',
            ],
            3,
        ];

        yield[
            'Not empty instance & no new attributes',
            new Attributes([
                'test-1' => 1,
                'test-2' => 2,
            ]),
            [],
            2,
        ];

        yield[
            'Not empty instance & new attributes with empty string as value and name',
            new Attributes([
                'test-1' => 1,
                'test-2' => 2,
                'test-3' => 3,
            ]),
            [
                'test-4' => null,
                'test-5' => '',
                ''       => '',
                'test-6' => 3,
            ],
            6,
        ];

        yield[
            'Not empty instance & standard, regular new attributes',
            new Attributes([
                'test-1' => 1,
                'test-2' => 2,
                'test-3' => 3,
            ]),
            [
                'test-4' => 1,
                'test-5' => 2,
                'test-6' => 3,
            ],
            6,
        ];

        yield[
            'Not empty instance & new attributes with names already added',
            new Attributes([
                'test-1' => 1,
                'test-2' => 2,
                'test-3' => 3,
            ]),
            [
                'test-1' => 1,
                'test-2' => 2,
                'test-4' => 3,
            ],
            4,
        ];
    }
}
