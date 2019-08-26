<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Common\FileUpload;
use DateTime;

class HolidayController extends VoyagerBreadController {

    private $PATH_DIR = '\holiday\execute\\';

    /**
     * set options to dataRow's details
     *
     * @param object &$dataRows
     * @param object $id
     * @param $mode : 1: create, 2: edit, 3: other (show)
     * @return void
     */
    protected function changeOptionInitData(&$dataRows, $mode, $id = null) {
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

                    if (count($line) != 6) {
                        $missingColumns = true;
                        break;
                    }
                    // $date_tmp = DateTime::createFromFormat('d/m/Y', array_get($line, 5))->format('Y/m/d');
                    // dd (date('Y-m-d', strtotime($date_tmp)));

                    // Data holiday
                    $dataImport['holiday'] = $this->convertShiftJIS([
                        'attribute' => (array_get($line, 0)) ? array_get($line, 0) : null,
                        'year' => array_get($line, 1),
                        'month' => array_get($line, 2),
                        'day' => array_get($line, 3),
                        'y_m_d' => (array_get($line, 4)) ? date('Y-m-d', strtotime(array_get($line, 4))) : null,
                        'holiday_name' => array_get($line, 5),
                        'register_date' => $createAt,
                        'update_date' => $updatedAt,
                    ]);
                    $data[] = $dataImport;
                    unset($dataImport);
                }

                if ($missingColumns) {
                    return redirect()->route("voyager.holiday.index")->with([
                        'message' => __('CSVの列が足らないので、もう一度チェックお願いします。'),
                        'alert-type' => 'error',
                    ]);
                }
                $holidayLogic = app(Holiday::class);
                DB::beginTransaction();
                try {
                    foreach ($data as $dataImport) {
                        $holidayImport = array_filter($dataImport['holiday']);
                        // Insert holiday
                        $holidayLogic->insert($holidayImport);
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
                return redirect()->route("voyager.holiday.index")->with([
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

                return redirect()->route("voyager.holiday.index")->with([
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
            return redirect()->route("voyager.holiday.index")->with([
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

    public function convertShiftJIS($data) {

        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = $this->convertToUTF8($value);
        }

        return $result;
    }

    function convertToUTF8($string) {

        if (! empty($string) && is_string($string)) {
            if (! mb_check_encoding($string, "UTF-8")) {
                $string = mb_convert_encoding($string, "UTF-8", "Shift-JIS, EUC-JP, JIS, SJIS, JIS-ms, eucJP-win, SJIS-win, ISO-2022-JP,
                            ISO-2022-JP-MS, SJIS-mac, SJIS-Mobile#DOCOMO, SJIS-Mobile#KDDI,
                            SJIS-Mobile#SOFTBANK, UTF-8-Mobile#DOCOMO, UTF-8-Mobile#KDDI-A,
                            UTF-8-Mobile#KDDI-B, UTF-8-Mobile#SOFTBANK, ISO-2022-JP-MOBILE#KDDI");
            }
        }

        return $string;
    }
}
