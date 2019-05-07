# Meritoo Menu Bundle

Common & useful classes, resources, extensions. Based on Symfony framework.

# Configuration

### All parameters of this bundle with default values

```yaml
meritoo_menu:
    templates:
        link: <a href="%%url%%"%%attributes%%>%%name%%</a>
        link_container: <div%%attributes%%>%%link%%</div>
        menu: <div%%attributes%%>%%linksContainers%%</div>
```

### Available parameters

* meritoo_menu.templates.link

    > Template of link in menu

    Default value: `<a href="%%url%%"%%attributes%%>%%name%%</a>`.

* meritoo_menu.templates.link_container

    > Template of container for a link
    
    Default value: `<div%%attributes%%>%%link%%</div>`

* meritoo_menu.templates.menu

    > Template of the whole menu (has containers with links)
    
    Default value: `<div%%attributes%%>%%linksContainers%%</div>`

# More

1. [**Configuration**](Configuration.md)
2. Twig extensions:
    - [MenuExtension](Twig-Extensions/MenuExtension.md)

[&lsaquo; Back to `Readme`](../README.md)
