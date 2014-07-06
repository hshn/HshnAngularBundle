Installation
------------

### Download HshnAngularBundle using composer

```bash
$ php composer.phar require hshn/angular-bundle
```

### Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Hshn\AngularBundle\HshnAngularBundle(),
    );
}
```

### Configure the HshnAngularBundle

```yaml
# app/config/config.yml
hshn_angular:
    template_cache:
        templates:
            app: # This will be angular module name
                targets:
                    # Specify a directory that angular templates contains.
                    # It will be a base directory of this module and a template url will be relative path of this base directory.
                    - @YourBundle/Resources/public/js
    assetic: ~
```

### Using AngularTemplateCache

In Twig:

If you configured as the sample above, the template cache of `app` module is available as `@ng_template_cache_app`.

```twig
{% javascripts
  '@ng_template_cache_app'%}
  <script src="{{ asset_url }}"></script>
{% endjavascripts %}
```

In Javascript:

If your directory structure is like this

```
If your bundle directory structure like that,
@YourBundle/Resource/public/js
├── bar
│   └── a.html
└── foo
    └── b.html
```

then you can use angular template in `app` module that named `bar/a.html` and `foo/b.html`.
