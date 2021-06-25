# Wikipedia-Field

Use Wikipedia as the source for your content.

## Config

You can configure the cache time by adding the `wikipedia` settings to the `fields` key in your `config/lit.php`

```php
'fields' => [
    // ...
    'wikipedia' => [
        'cache_ttl' => 60 * 60 * 24,
    ],
],
```

## Usage

The wikipedia formfield is used as follows:

```php
// provide the keys url, section (optional) and chars (optional)
$form->wikipedia('wiki');
```

If you want to disable the `section` or `chars` inputs you can do it as follows:

```php
$form->wikipedia('wiki')->section(false)->chars(false);
```

### Displaying Content

In order to load the content of a wikipedia article you can use the `Wikipedia` facade.

```php
// will output the first 'intro' section of the article
Wikipedia::load('https://en.wikipedia.org/wiki/PHP');
```

You can also select a specific section:

```php
// will output the 'Mascot' section.
Wikipedia::load('https://en.wikipedia.org/wiki/PHP', 'Mascot');
```

You might as well set a maximum amout of characters:

```php
// will output the first 100 chars of the 'Mascot' section.
Wikipedia::load('https://en.wikipedia.org/wiki/PHP', 'Mascot', 100);
```
