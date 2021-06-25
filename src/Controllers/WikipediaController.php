<?php

namespace Litstack\Wikipedia\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Litstack\Wikipedia\Requests\WikipediaPreviewRequest;

class WikipediaController
{
    /**
     * Load wikipedia content.
     *
     * @param  string      $wikipedia_url
     * @param  string|null $section
     * @param  int|null    $chars
     * @return string
     */
    public function load(string $wikipedia_url, ?string $section = null, ?int $chars = null): string
    {
        $api_url = $this->getApiUrl($wikipedia_url);

        $page = $this->getPage($wikipedia_url);

        return Cache::remember("$wikipedia_url.$section.$chars", config('lit.fields.wikipedia.cache_ttl') ?: 60 * 60, function () use ($section, $api_url, $page, $chars) {
            if ($section) {
                return $this->getSection($api_url, $page, $section, $chars);
            }

            return $this->getSummary($api_url, $page, $chars);
        });
    }

    public function preview(WikipediaPreviewRequest $request)
    {
        return $this->load(
            $request->url,
            $request->section,
            $request->chars,
        );
    }

    /**
     * Get summary of a wikipedia article.
     *
     * @param  $string $api_url
     * @param  $string $page
     * @return string
     */
    protected function getSummary($api_url, $page, ?int $chars = null): string
    {
        $response = Http::get($api_url, [
            'format'      => 'json',
            'redirects'   => 1,
            'action'      => 'query',
            'prop'        => 'extracts',
            'explaintext' => true,
            'titles'      => $page,
            'exchars'     => $chars ?: 9999999999999,
        ]);

        $text = '';
        foreach ($response['query']['pages'] as $key => $page) {
            $text .= $page['extract'];
        }

        return explode('==', $text)[0];
    }

    /**
     * Get section of a wikipedia article.
     *
     * @param  string   $api_url
     * @param  string   $page
     * @param  string   $section
     * @param  int|null $chars
     * @return string
     */
    public function getSection(string $api_url, string $page, string $section, ?int $chars = null): string
    {
        $section_id = $this->getSectionId($api_url, $section, $page);

        $response = Http::get($api_url, [
            'action'  => 'parse',
            'format'  => 'json',
            'page'    => $page,
            'section' => $section_id,
        ]);

        $html = (array) json_decode($response->getBody())->parse->text;
        $html = $html['*'];

        $output = $this->stripContent($html, $section, $chars);

        return $output;
    }

    /**
     * Get API-Url from a normal Wikipedia-URL.
     *
     * @param  string      $wikipedia_url
     * @param  string|null $section
     * @return string
     */
    public function getApiUrl(string $wikipedia_url): string
    {
        $parsedUrl = parse_url($wikipedia_url);
        $locale = explode('.', $parsedUrl['host'])[0];

        return 'https://'.$locale.'.wikipedia.org/w/api.php?';
    }

    /**
     * Get page url-parameter from Wikipedia-URL.
     *
     * @param  string $wikipedia_url
     * @return string
     */
    public function getPage(string $wikipedia_url): string
    {
        $segments = explode('/', $wikipedia_url);

        return urldecode(end($segments));
    }

    /**
     * Get id of Wikipedia section by title.
     *
     * @param  string $api_url
     * @param  string $section
     * @param  string $page
     * @return int
     */
    public function getSectionId(string $api_url, string $section, string $page): int
    {
        $response = Http::get($api_url, [
            'format' => 'json',
            'action' => 'parse',
            'prop'   => 'sections',
            'page'   => $page,
        ]);

        $sections = collect((array) json_decode($response->getBody())->parse->sections);

        return $section ? $sections->where('line', $section)->first()->index : 0;
    }

    /**
     * Remove unnecessary junk from wikipedia api output.
     *
     * @param  string $html
     * @return string
     */
    public function stripContent(string $html, ?string $section = null, ?int $chars = null): string
    {
        // remove everything before h2 if not first section
        if ($section) {
            $html = strstr($html, '<h2>');
        }

        // remove everything until first paragraph
        $html = strstr($html, '<p>');

        $regexpattern = [
            '/<div class="mw-parser-output".*?<\/div>/s',
            '/<div class="mw-references-wrap".*?<\/div>/s',
            '/<div class="mbox-text-span".*?<\/div>/s',
            '/<span class="mw-editsection-bracket".*?<\/span>/s',
            '/<span class="mw-editsection-divider".*?<\/span>/s',
            '/<span class="mw-editsection".*?<\/span>/s',
            '/<ol.*?<\/ol>/s',
            '/<div class="thumb tright".*?\\n/s',
            '/<div class="thumb tleft".*?\\n/s',
            '/<h2.*?\<\/h2>/s',
            '/<sup.*?\<\/sup>/s',
            '/<!--.*?\-->/s',

        ];

        foreach ($regexpattern as $pattern) {
            $replacement = '';
            $html = preg_replace($pattern, $replacement, $html);
        }

        $text = strip_tags($html);

        if ($chars) {
            // we don't want new lines in our preview
            $text_only_spaces = preg_replace('/\s+/', ' ', $text);
            // truncates the text + 3 to end after ". " iff
            $text_truncated = mb_substr($text_only_spaces, 0, mb_strpos($text_only_spaces, ' ', $chars + 3));
            // prevents last word truncation
            return trim(mb_substr($text_truncated, 0, mb_strrpos($text_truncated, ' ')));
        }

        return $text;
    }
}
