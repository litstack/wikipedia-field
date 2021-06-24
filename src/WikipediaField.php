<?php

namespace Litstack\Wikipedia;

use Closure;
use Ignite\Crud\BaseField;
use Ignite\Crud\Models\LitFormModel;

class WikipediaField extends BaseField
{
    /**
     * Vue component name.
     *
     * @var string
     */
    protected $component = 'lit-wikipedia';

    protected $filler;

    /**
     * Create a new Wikipedia instance.
     *
     * @param string      $url
     * @param string|null $section
     * @param int|null    $chars
     */
    public function __construct(string $url, ?string $section = null, ?string $chars = null)
    {
        parent::__construct("{$url}_{$section}_{$chars}");

        $this->urlKey($url);
        if ($section) {
            $this->sectionKey($section);
        }
        if ($chars) {
            $this->charsKey($chars);
        }

        $this->title('Wikipedia');
    }

    /**
     * Set field defaults.
     *
     * @return void
     */
    public function mount()
    {
        //
    }

    public function urlKey($key)
    {
        $this->setAttribute('url_key', $key);
    }

    public function sectionKey($key)
    {
        $this->setAttribute('section_key', $key);
    }

    public function charsKey($key)
    {
        $this->setAttribute('chars_key', $key);
    }

    public function fillModel($model, $attributeName, $attributeValue)
    {
        if (! is_array($attributeValue)) {
            return;
        }
        // dd($attributeValue[$this->url_key]);

        if ($model instanceof LitFormModel) {
            return;
        }

        $model->{$this->url_key} = $attributeValue[$this->url_key];
        if ($this->section_key) {
            $model->{$this->section_key} = $attributeValue[$this->section_key];
        }
        if ($this->chars_key) {
            $model->{$this->chars_key} = $attributeValue[$this->chars_key];
        }
    }

    public function fill(Closure $closure)
    {
        $this->filler = $closure;
    }
}
