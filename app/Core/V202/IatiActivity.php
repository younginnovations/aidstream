<?php namespace App\Core\V202;

use App\Core\V201\IatiActivity as V201;
use Illuminate\Support\Str;

class IatiActivity extends V201
{
    public function getParticipatingOrganization()
    {
        return app('App\Core\V202\Element\Activity\ParticipatingOrganization');
    }

    public function getBudget()
    {
        return app('App\Core\V202\Element\Activity\Budget');
    }

    public function getBudgetRequest()
    {
        return app('App\Core\V202\Requests\Activity\Budget');
    }

    public function getPlannedDisbursement()
    {
        return app('App\Core\V202\Element\Activity\PlannedDisbursement');
    }

    public function getPlannedDisbursementRequest()
    {
        return app('App\Core\V202\Requests\Activity\PlannedDisbursement');
    }

    public function getRecipientRegion()
    {
        return app('App\Core\V202\Element\Activity\RecipientRegion');
    }

    public function getRecipientRegionRequest()
    {
        return app('App\Core\V202\Requests\Activity\RecipientRegion');
    }

    public function getSector()
    {
        return app('App\Core\V202\Element\Activity\Sector');
    }

    public function getSectorRequest()
    {
        return app('App\Core\V202\Requests\Activity\Sector');
    }

    public function getPolicyMaker()
    {
        return app('App\Core\V202\Element\Activity\PolicyMaker');
    }

    public function getPolicyMakerRequest()
    {
        return app('App\Core\V202\Requests\Activity\PolicyMaker');
    }

    public function getTransaction()
    {
        return app('App\Core\V202\Element\Activity\Transaction');
    }

    public function getTransactionRequest()
    {
        return app('App\Core\V202\Requests\Activity\Transaction');
    }

    public function getDocumentLink()
    {
        return app('App\Core\V202\Element\Activity\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V202\Requests\Activity\DocumentLink');
    }

    public function getResult()
    {
        return app('App\Core\V202\Element\Activity\Result');
    }

    public function getResultRequest()
    {
        return app('App\Core\V202\Requests\Activity\Result');
    }

    /**
     * return versioned activity class
     * @param $name
     * @param $versionedDir
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getFile($name, $versionedDir)
    {
        return app(sprintf('App\Core\V202\%s\Activity\%s', $versionedDir, $name));
    }

    /**
     * return versioned activity file path info
     * @param $method
     * @return array
     */
    protected function getPathInfo($method)
    {
        $versionedDirs = [
            'Element'    => 'Element',
            'Request'    => 'Requests',
            'Repository' => 'Repositories'
        ];

        preg_match_all('/[A-Z][a-z]+/', $method, $matches);
        $fileType     = end($matches[0]);
        $versionedDir = $versionedDirs[$fileType];

        return [$fileType, $versionedDir];
    }

    /**
     * handel method calls dynamically starting with get
     * @param $method
     * @param $parameters
     * @return \Illuminate\Foundation\Application|mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $pathInfo = $this->getPathInfo($method);
            $name     = str_replace(['get', $pathInfo[0]], '', $method);

            return $this->getFile($name, $pathInfo[1]);
        }
        throw new BadMethodCallException();
    }

    public function getChangeActivityDefault()
    {
        return app('App\Core\V202\Element\Activity\ChangeActivityDefault');
    }
}
