<?php namespace App\Http\Controllers\Lite\CsvDownload;


use App\Http\Controllers\Lite\LiteController;
use App\Lite\Services\CsvDownload\CsvDownloadService;
use App\Services\Download\DownloadCsvManager;
use App\Services\Export\CsvGenerator;
use Illuminate\Support\Facades\Session;

/**
 * Class CsvDownloadController
 * @package App\Http\Controllers\Lite\CsvDownload
 */
class CsvDownloadController extends LiteController
{
    /**
     * @var CsvGenerator
     */
    protected $csvGenerator;

    /**
     * @var CsvDownloadService
     */
    private $downloadService;

    /**
     * CsvDownloadController constructor.
     * @param CsvDownloadService $downloadService
     * @param CsvGenerator       $csvGenerator
     */
    public function __construct(CsvDownloadService $downloadService, CsvGenerator $csvGenerator)
    {
        $this->middleware('auth');
        $this->csvGenerator    = $csvGenerator;
        $this->downloadService = $downloadService;
    }

    /**
     * Export the simple fields of activities in csv format.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadSimpleCsv()
    {
        if (session('version') == 'V201') {
            return redirect()->route('lite.activity.index')->withResponse(['messages' => [trans('error.not_available_for_v201')], 'type' => 'warning']);
        }

        $activities = $this->downloadService->simpleData(session('org_id'), session('version'));
        $headers    = $activities['headers'];
        unset($activities['headers']);
        $this->csvGenerator->generateWithHeaders('Simple', $activities, $headers);
    }
}
