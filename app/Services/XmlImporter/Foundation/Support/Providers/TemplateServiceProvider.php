<?php namespace App\Services\XmlImporter\Foundation\Support\Providers;


/**
 * Class TemplateServiceProvider
 * @package App\Services\XmlImporter\Foundation\Support\Providers
 */
class TemplateServiceProvider
{
    /**
     * Relative path for the template files.
     *
     * @var string
     */
    protected $relativeTemplatePath = '/Services/XmlImporter/Foundation/Support/Templates';

    /**
     * Template for a specific Xml version.
     *
     * @var null
     */
    protected $template = null;

    /**
     * Get the template for a specific Xml version.
     *
     * @param null $key
     * @return null
     */
    public function get($key = null)
    {
        if (!$key) {
            return $this->template;
        }

        return $this->template[$key];
    }

    /**
     * Load template for a specific version.
     *
     * @param string $version
     * @return array
     */
    public function load($version = '2.02')
    {
        return json_decode($this->read($version), true);
    }

    /**
     * Read the template file.
     *
     * @param $version
     * @return string
     */
    protected function read($version)
    {
        return file_get_contents(sprintf('%s/%s.json', $this->templatePath(), $this->clean($version)));
    }

    /**
     * Remove unwanted '.' character from the IATI version.
     *
     * @param $version
     * @return string
     */
    protected function clean($version)
    {
        return 'V' . str_replace('.', '', $version);
    }

    /**
     * @return string
     */
    protected function templatePath()
    {
        return app_path() . $this->relativeTemplatePath();
    }

    /**
     * Get the relative path for the template files.
     * @return string
     */
    protected function relativeTemplatePath()
    {
        return $this->relativeTemplatePath;
    }
}
