# Wikipedia-Field

Use Wikipedia as the source for your content.

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

If no `section` is given, the intro paragraph of the article will be taken.

```php
Wikipedia::load($form->url, $form->section, $form->chars);
```
