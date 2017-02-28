<?php namespace App\Services\XmlImporter\Foundation\Support\Factory;

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Activity as V1Activity;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\V105\Activity as V105Activity;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Activity as V2Activity;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Elements\Result as V1Result;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements\Result as V2Result;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Elements\Transaction as V1Transaction;
use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements\Transaction as V2Transaction;

/**
 * Class XmlImportFactory
 * @package App\Services\XmlImporter\Foundation\Support\Factory
 */
trait Mapper
{
    /**
     * Mapper bindings according to the Xml Version.
     *
     * @var array
     */
    protected $bindings = [
        '1.03' => [V1Activity::class, V1Transaction::class, V1Result::class],
        '1.05' => [V105Activity::class, V1Transaction::class, V1Result::class],
        '2.01' => [V2Activity::class, V2Transaction::class, V2Result::class],
        '2.02' => [V2Activity::class, V2Transaction::class, V2Result::class]
    ];

    /**
     * Initialize XmlMapper components according to the Xml Version.
     *
     * @return mixed
     */
    public function initComponents()
    {
        $this->iatiActivity = null;

        list($this->activity, $this->transactionElement, $this->resultElement) = $this->getMapping($this->version);
    }

    /**
     * Get the mapping for a specific version.
     *
     * @param $version
     * @return mixed
     */
    protected function getMapping($version)
    {
        $elements = [];

        foreach ($this->getBindings($version) as $binding) {
            $elements[] = app()->make($binding);
        }

        return $elements;
    }

    /**
     * Get the binding for any specific version.
     *
     * @param $version
     * @return mixed
     */
    protected function getBindings($version)
    {
        return $this->bindings[$version];
    }
}
