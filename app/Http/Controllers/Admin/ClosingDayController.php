<?php

namespace App\Http\Controllers\Admin;

use App\PaymentAgency;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\ClosingDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Common\FileUpload;

class ClosingDayController extends VoyagerBreadController {

    private $PATH_DIR = '\closing-day\execute\\';

    /**
     * set options to dataRow's details
     *
     * @param object &$dataRows
     * @param object $id
     * @param $mode : 1: create, 2: edit, 3: other (show)
     * @return void
     */
    protected function changeOptionInitData(&$dataRows, $mode, $id = null) {

        $payment_agency_list = PaymentAgency::all();
        foreach ($dataRows as &$row) {
            $options[0] = null;
            $defaults = '';

            if ('payment_agency_id' == $row->field) {
                foreach ($payment_agency_list as $value) {
                    $options[$value->id] = $value->agency_name;
                }
            }
            $row->details = json_encode(['default' => $defaults, 'options' => $options]);

        }
    }

    public function execute(Request $request) {

        $csv_file = $request->file('csv_file');
        if (! is_null($csv_file)) {
            // get path
            $path = $request->file('csv_file')->getRealPath();
            $file = $this->getFileReader($path); // Ex: "~/data.csv"
            $row = 0;

            // get extension file
            $extensionFile = $request->file('csv_file')->getClientOriginalExtension();
            // extensions system support
            $extensions = array ("csv", "xls", "xlsx");
            // check extension file have in extensions
            if (in_array($extensionFile, $extensions) === true) {

                $contents = array ();
                while (($data = fgetcsv($file, 2000, ",")) !== FALSE) {
                    // check header
                    if ($row == 0) {
                        $row++;
                        continue;
                    }

                    $contents[] = $data;
                }
                fclose($file);

                $data = array ();
                $missingColumns = false;

                foreach ($contents as $idx => $line) {

                    $createAt = $updatedAt = Carbon::now()->toDateTimeString();

                    if (count($line) != 8) {
                        $missingColumns = true;
                        break;
                    }
                    // $date_tmp = DateTime::createFromFormat('d/m/Y', array_get($line, 5))->format('Y/m/d');
                    // dd (date('Y-m-d', strtotime($date_tmp)));

                    // Data closing day
                    $dataImport['closing_day'] = ([
                        'transfer_day' => array_get($line, 0),
                        'transfer_month' => array_get($line, 1),
                        'transfer_request' => (array_get($line, 2)) ? date('Y-m-d', strtotime(array_get($line, 2))) : null,
                        'deadline' => (array_get($line, 3)) ? date('Y-m-d', strtotime(array_get($line, 3))) : null,
                        'transfer_date' => (array_get($line, 4)) ? date('Y-m-d', strtotime(array_get($line, 4))) : null,
                        'result_date' => (array_get($line, 5)) ? date('Y-m-d', strtotime(array_get($line, 5))) : null,
                        'payment_date' => (array_get($line, 6)) ? date('Y-m-d', strtotime(array_get($line, 6))) : null,
                        'payment_agency_id' => array_get($line, 7),
                        'register_date' => $createAt,
                        'update_date' => $updatedAt,
                    ]);
                    $data[] = $dataImport;
                    unset($dataImport);
                }

                if ($missingColumns) {
                    return redirect()->route("voyager.closing-day.index")->with([
                        'message' => __('CSVの列が足らないので、もう一度チェックお願いします。'),
                        'alert-type' => 'error',
                    ]);
                }
                $closingDayLogic = app(ClosingDay::class);
                DB::beginTransaction();
                try {
                    foreach ($data as $dataImport) {
                        $closingDayImport = array_filter($dataImport['closing_day']);
                        // Insert closing day
                        $closingDayLogic->insert($closingDayImport);
                    }
                    DB::commit();
                    // $message = ('CSVをインポートしました');
                    // $alert_type = 'success';
                    $message = array (
                        'message' => "CSVをインポートしました。",
                        'alert-type' => 'success'
                    );
                } catch (\Exception $e) {
                    DB::rollBack();
                    dd($e->getMessage());

                    // $message = '問題が発生させたので、もう一度チェックお願いします。';
                    // $alert_type = 'error';
                    $message = array (
                        'message' => "問題が発生させたので、もう一度チェックお願いします。",
                        'alert-type' => 'error'
                    );
                }
                return redirect()->route("voyager.closing-day.index")->with([
                    'message' => $message
                ]);
                // ->with([
                // 'message' => $message,
                // 'alert-type' => $alert_type,
                //  ]);

            } else {
                // $message = __('ちょうどファイルcsvをサポートしたり');
                // $alert_type = 'error';
                $message = array (
                    'message' => "問題が発生させたので、もう一度チェックお願いします。",
                    'alert-type' => 'error'
                );

                return redirect()->route("voyager.closing-day.index")->with([
                    'message' => $message
                ]);
            }

        } else {
            // $message = __('ファイルを確認してください。');
            // $alert_type = 'error';
            $message = array (
                'message' => "ファイルを確認してください。",
                'alert-type' => 'error'
            );
            return redirect()->route("voyager.closing-day.index")->with([
                'message' => $message
            ]);
        }
    }

    private function getFileReader($target_file) {

        $current_locale = setlocale(LC_ALL, '0'); // Backup current locale.
        //dd ($target_file);
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        // Read the file content in SJIS-Win.
        $content = file_get_contents($target_file);

        // Save the file as UTF-8 in a temp location.
        $fp = tmpfile();
        fwrite($fp, $content);
        rewind($fp);

        setlocale(LC_ALL, $current_locale); // Restore the backed-up locale.

        return $fp;
    }
}
