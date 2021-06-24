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
$form->wikipedia('url', 'section', 'chars');
```

The formfield will show the single inputs for the `url`, `section` (if provided), and `chars` (if provided). It will also show a preview button that you can use for validation and pre-caching the content.

If you use the field in a model, you have to provide columns for the `url`, `section` (optional), and `chars` (optional) in your database.
If no `section` is given, the intro paragraph of the article will be taken.
If no `chars` is given, the content length is not limited.

### Displaying Content

In order to load the content of a wikipedia article you can use the `Wikipedia` facade.

```php
Wikipedia::load('https://en.wikipedia.org/wiki/PHP'); // will output the first 'intro' section of the article
```

You can also select a specific section:

```php
Wikipedia::load('https://en.wikipedia.org/wiki/PHP', 'Mascot'); // will output the 'Mascot' section.
```

You might as well set a maximum amout of characters:

```php
Wikipedia::load('https://en.wikipedia.org/wiki/PHP', 'Mascot', 100); // will output the first 100 chars of the 'Mascot' section.
```

You can load the cached data with the following facade:

```php
Wikipedia::load(
    $model->url, // a valid wikipedia url
    $model->section, // the headline of a section in the article
    $model->chars,  // max chars
);
```

When using the field in a `form`, the values are stored in an array with a title of all keys combined, e.g.:

```php
// in the form config:
$form->wikipedia('wiki_url', 'wiki_section', 'max_chars');

// retrieve the data
$wikipedia = $form->wiki_url_wiki_section_max_chars;
Wikipedia::load(
    $wikipedia['wiki_url'],
    $wikipedia['wiki_section'],
    $wikipedia['max_chars'],
);
```
