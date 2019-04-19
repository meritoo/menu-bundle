# Meritoo Menu Bundle

Build navigation easily, without any efforts. Bundle based on Symfony framework.

# MenuExtension

Located here: `Meritoo\MenuBundle\Twig\MenuExtension`. It's Twig extension related to menu. Allows to render menu.

### Functions

##### meritoo_menu()

Renders menu with given items. Expects 2 arguments:

- `$links` - an array of arrays (0-based indexes): [0] name of link, [1] url of link, [2], (optional) attributes of link, [3] (optional) attributes of item
- `$menuAttributes` - (optional) attributes of the main container. It's an array of key-value pairs, where key - attribute, value - value of attribute

```twig
{% set links = [
    [
        'Test 1',
        '/test',
        {
            'id': 'main'
        },
        {
            'data-show': 'test',
            'class': 'my-big-class'
        }
    ],
    [
        'Test 2',
        '/test/2',
        {
            'id': 'email',
            'class': 'blue'
        }
    ],
    [
        'Test 3',
        '/test/46/test',
        {
            'data-show': 'test',
            'class': 'my-big-class'
        },
        {
            'id': 'test-test',
            'data-show': 'true',
            'class': 'my-last-class'
        }
    ]
] %}

{% set menuAttributes = {
    'id': 'main',
    'class': 'my-menu'
} %}

{{ meritoo_menu(links, menuAttributes) }}
```

Result:

```html
<div id="main" class="my-menu">
    <div data-show="test" class="my-big-class">
        <a href="/test" id="main">Test 1</a>
    </div>
    <div>
        <a href="/test/2" id="email" class="blue">Test 2</a>
    </div>
    <div id="test-test" data-show="true" class="my-last-class">
        <a href="/test/46/test" data-show="test" class="my-big-class">Test 3</a>
    </div>
</div>
```

# More

1. [Configuration](../Configuration.md)
2. Twig extensions:
    - [**MenuExtension**](MenuExtension.md)

[&lsaquo; Back to `Readme`](../../README.md)
