<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Domain\Html;

use Meritoo\Common\Collection\Collection;
use Meritoo\Common\Utilities\Arrays;

/**
 * HTML attributes of element, e.g. "class", "id"
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Attributes extends Collection
{
    /**
     * Name of attribute that stores CSS class
     *
     * @var string
     */
    public const ATTRIBUTE_CSS_CLASS = 'class';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $attributes = [])
    {
        $filtered = $this->removeEmptyNames($attributes);
        parent::__construct($filtered);
    }

    /**
     * Returns string representation of attributes
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }

    /**
     * {@inheritdoc}
     */
    public function addMultiple($attributes, $useIndexes = false): Attributes
    {
        $filtered = $this->removeEmptyNames($attributes);
        $result = parent::addMultiple($filtered, true);

        /* @var Attributes $result */
        return $result;
    }

    /**
     * Returns attributes represented as string (prepared to use/display as string)
     *
     * @return string
     */
    public function asString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return Arrays::valuesKeys2string($this->toArray(), ' ', '=', '"');
    }

    /**
     * Removes attributes with empty names
     *
     * @param array $attributes Attributes to verify
     * @return array
     */
    private function removeEmptyNames(array $attributes): array
    {
        return array_filter($attributes, static function (string $name) {
            return '' !== $name;
        }, ARRAY_FILTER_USE_KEY);
    }
}
