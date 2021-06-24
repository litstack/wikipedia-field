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
// provide the keys url, section (optional) and chars (optional)
$form->wikipedia('url', 'section', 'chars');
```

If you use the field in a model, you have to provide columns for the `url`, `section` (optional), and `chars` (optional) in your database.
If no `section` is given, the intro paragraph of the article will be taken.
If no `chars` is given, the content length is not limited.

```php
Wikipedia::load($form->url, $form->section, $form->chars);
```
