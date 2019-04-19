# Meritoo Menu Bundle

Common & useful classes, resources, extensions. Based on Symfony framework.

# Configuration

### All parameters of this bundle with default values

```yaml
meritoo_menu:
    templates:
        link: <a href="%%url%%"%%attributes%%>%%name%%</a>
        item: <div%%attributes%%>%%link%%</div>
        menu: <div%%attributes%%>%%items%%</div>
```

### Available parameters

* meritoo_menu.templates.link

    > Template for a link in menu

    Default value: `<a href="%%url%%"%%attributes%%>%%name%%</a>`.

* meritoo_menu.templates.item

    > Template for an item in menu (container for a link)
    
    Default value: `<div%%attributes%%>%%link%%</div>`

* meritoo_menu.templates.menu

    > Template for the whole menu (container for items)
    
    Default value: `<div%%attributes%%>%%items%%</div>`

# More

1. [**Configuration**](Configuration.md)
2. Twig extensions:
    - [MenuExtension](Twig-Extensions/MenuExtension.md)

[&lsaquo; Back to `Readme`](../README.md)
