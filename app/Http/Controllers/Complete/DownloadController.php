<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Controller;
use App\Services\Download\DownloadCsvManager;
use App\Services\Export\CsvGenerator;

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
    /*
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
        $csvData = $this->downloadCsvManager->simpleCsvData();
        $headers = $csvData['headers'];
        unset($csvData['headers']);
        $this->generator->generateWithHeaders('simple', $csvData, $headers);
    }


    /**
     * export the complete activity csv
     */
    public function exportCompleteCsv()
    {
        $this->downloadCsvManager->completeCsvData();
    }
}
