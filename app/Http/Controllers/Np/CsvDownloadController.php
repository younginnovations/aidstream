<?php namespace App\Http\Controllers\Np;


use App\Http\Controllers\Controller;
use App\Np\Services\CsvDownload\CsvDownloadService;
use App\Services\Download\DownloadCsvManager;
use App\Services\Export\CsvGenerator;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\Session;

/**
 * Class CsvDownloadController
 * @package App\Http\Controllers\Np\CsvDownload
 */
class CsvDownloadController extends Controller
{
    /**
     * @var CsvGenerator
     */
    protected $csvGenerator;

    /**
     * @var CsvDownloadService
     */
    protected $downloadService;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * CsvDownloadController constructor.
     * @param CsvDownloadService $downloadService
     * @param CsvGenerator $csvGenerator
     * @param OrganizationManager $organizationManager
     */
    public function __construct(CsvDownloadService $downloadService, CsvGenerator $csvGenerator, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->csvGenerator = $csvGenerator;
        $this->downloadService = $downloadService;
        $this->organizationManager = $organizationManager;
    }

    /**
     * Export the simple fields of activities in csv format.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadSimpleCsv()
    {
        if (session('version') == 'V201') {
            return redirect()->route('np.activity.index')->withResponse(['messages' => [trans('error.not_available_for_v201')], 'type' => 'warning']);
        }

        $activities = $this->downloadService->simpleData(session('org_id'), session('version'));

        if (count($activities) > 0) {
            return $this->csvGenerator->generate($this->getOrgName(), $activities);
        }

        return redirect()->back()->withResponse(['messages' => [trans('error.none_activity')], 'type' => 'warning']);
    }

    protected function getOrgName()
    {
        $organization = $this->organizationManager->getOrganization(session('org_id'));

        return getVal((array)$organization->reporting_org, [0, 'narrative', 0, 'narrative'], 'No name');
    }
}
