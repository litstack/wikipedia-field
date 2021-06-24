# wikipedia-field

https://de.wikipedia.org/w/api.php?action=parse&page=Kay_H._Nebel&format=json&section=' . \$targetSection

## Config

You can configure the cache time by adding the `wikipedia` settings to the `fields` key in your `config/lit.php`

```php
'fields' => [
    ...
    'wikipedia' => [
        'cache_ttl' => 60 * 60 * 24,
    ],
],
```

## Usage

The wikipedia formfield is used as follows:

```php
$form->wikipedia('url', 'section', 'chars');
```

```php
Wikipedia::load($form->url, $form->section, $form->chars);
```
