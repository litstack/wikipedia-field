<?php

namespace Litstack\Wikipedia;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Traits\TranslatableField;

class WikipediaField extends BaseField
{
    use TranslatableField;

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
     * @param string      $col
     * @param string|null $section
     * @param int|null    $chars
     */
    public function __construct(string $col)
    {
        parent::__construct($col);
    }

    /**
     * Set field defaults.
     *
     * @return void
     */
    public function mount()
    {
        $this->title('Wikipedia');
        $this->section();
        $this->chars();
    }

    /**
     * Set section visibility.
     *
     * @param bool $section
     *
     * @return $this
     */
    public function section(bool $section = true)
    {
        $this->setAttribute('section', $section);

        return $this;
    }

    /**
     * Set chars visibility.
     *
     * @param bool $chars
     *
     * @return $this
     */
    public function chars(bool $chars = true)
    {
        $this->setAttribute('chars', $chars);

        return $this;
    }
}
