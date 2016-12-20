<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Controller;
use App\Services\Download\DownloadCsvManager;
use App\Services\Export\CsvGenerator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
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
        $this->middleware('auth');
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
            return redirect()->back()->withResponse(['messages' => [trans('error.none_activity')], 'type' => 'warning']);
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
            return redirect()->back()->withResponse(['messages' => [trans('error.something_is_not_right')], 'type' => 'danger']);
        }

        if (false === $csvData) {
            return redirect()->back()->withResponse(['messages' => [trans('error.none_activity')], 'type' => 'warning']);
        }

        $this->generator->generate('complete', $csvData);
    }

    /**
     * Export Transaction  Csv (Generated as 'Transaction.csv')
     */
    public function exportTransactionCsv()
    {
        $csvData = $this->downloadCsvManager->transactionCsvData(Session::get('org_id'));

        if (false === $csvData) {
            return redirect()->back()->withResponse(['messages' => [trans('error.none_activity')], 'type' => 'warning']);
        }

        if (null === $csvData && false !== $csvData) {
            return redirect()->back()->withResponse(['messages' => [trans('error.none_transaction')], 'type' => 'warning']);
        }

        $headers = $csvData['headers'];
        unset($csvData['headers']);
        $this->generator->generateWithHeaders('transaction', $csvData, $headers);
    }

    /**
     * download Detailed Transaction Template as 'iati_transaction_template_detailed.csv'
     * @return mixed
     */
    public function downloadDetailedTransactionTemplate()
    {
        $pathToFile = app_path("Core/" . session()->get('version') . "/Files/Csv/iati_transaction_template_detailed.csv");
        if (!File::exists($pathToFile)) {
            $pathToFile = app_path("Core/" . config('app.default_version_name') . "/Files/Csv/iati_transaction_template_detailed.csv");
        }

        return Response::download($pathToFile);
    }

    /**
     * download simple transaction template as 'iati_transaction_template_simple.csv'
     * @return mixed
     */
    public function downloadSimpleTransactionTemplate()
    {
        $pathToFile = app_path("Core/" . session()->get('version') . "/Files/Csv/iati_transaction_template_simple.csv");
        if (!File::exists($pathToFile)) {
            $pathToFile = app_path("Core/" . config('app.default_version_name') . "/Files/Csv/iati_transaction_template_simple.csv");
        }

        return Response::download($pathToFile);
    }

    /**
     * download activity template as 'iati_activity_template.csv'
     * @return mixed
     */
    public function downloadActivityTemplate()
    {
        $pathToFile = app_path("Core/" . session()->get('version') . "/Files/Csv/iati_activity_template.csv");
        if (!File::exists($pathToFile)) {
            $pathToFile = app_path("Core/" . config('app.default_version_name') . "/Files/Csv/iati_activity_template.csv");
        }

        return Response::download($pathToFile);
    }

    /**
     * download activity template as 'iati_activity_template2.csv'
     * @return mixed
     */
    public function downloadActivityTemplate2()
    {
        $pathToFile = app_path("Core/" . session()->get('version') . "/Files/Csv/iati_activity_template2.csv");
        if (!File::exists($pathToFile)) {
            $pathToFile = app_path("Core/" . config('app.default_version_name') . "/Files/Csv/iati_activity_template2.csv");
        }

        return Response::download($pathToFile);
    }
}
