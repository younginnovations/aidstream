<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Controller;
use App\Services\Download\DownloadCsvManager;
use App\Services\Export\CsvGenerator;
use Illuminate\Support\Facades\Session;

/**
 * Class DownloadController
 * @package App\Http\Controllers\Complete
 */
class DownloadController extends Controller
{
    /**
     * @var DownloadCsvManager
     */
    protected $downloadCsvManager;

    /**
     * @var CsvGenerator
     */
    protected $generator;

    /**
     * @param DownloadCsvManager $downloadCsvManager
     * @param CsvGenerator       $generator
     */
    function __construct(DownloadCsvManager $downloadCsvManager, CsvGenerator $generator)
    {
        $this->downloadCsvManager = $downloadCsvManager;
        $this->generator          = $generator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('downloads.index');
    }

    /**
     * export the simple activity csv
     */
    public function exportSimpleCsv()
    {
        $csvData = $this->downloadCsvManager->simpleCsvData(Session::get('org_id'));

        if (false === $csvData) {
            return redirect()->back()->withResponse(['messages' => ["It seems you do not have any Activities."], 'type' => 'warning']);
        }

        $headers = $csvData['headers'];
        unset($csvData['headers']);
        $this->generator->generateWithHeaders('simple', $csvData, $headers);
    }

    /**
     * Export Complete Csv (Generated as 'complete.csv')
     */
    public function exportCompleteCsv()
    {
        $csvData = $this->downloadCsvManager->completeCsvData(Session::get('org_id'));

        if (is_null($csvData)) {
            return redirect()->back()->withResponse(['messages' => ["Something doesn't seem to be right."], 'type' => 'danger']);
        }

        if (false === $csvData) {
            return redirect()->back()->withResponse(['messages' => ["It seems you do not have any Activities."], 'type' => 'warning']);
        }

        $this->generator->generate('complete', $csvData);
    }
}
