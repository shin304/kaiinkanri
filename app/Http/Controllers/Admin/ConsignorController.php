<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use TCG\Voyager\Facades\Voyager;
use Validator;

class ConsignorController extends VoyagerBreadController
{
    private $_hankakumojiRegix = '^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$';

    public function index(Request $request)
    {
        $this->clearOldInput();

        return parent::index($request);
    }
    // POST BRE(A)D
    public function store(Request $request)
    {
        $this->clearOldInput();
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        // Validation
        $rules = [
            // 'consignor_name' => 'required',
            'agency_name' => 'required',
            'base_name' => 'required',
            'withdrawal_day1' => 'numeric|min:1|max:31',
            'withdrawal_day2' => 'numeric|min:1|max:31',
            'withdrawal_day3' => 'numeric|min:1|max:31',
            'withdrawal_day4' => 'numeric|min:1|max:31',
            'withdrawal_day5' => 'numeric|min:1|max:31',
        ];
        $messages = [
            // 'consignor_name.required' => '収納代行会社名が必須です。',
            'agency_name.required' => '収納代行会社名が必須です。',
            'base_name.required' => 'ベースURLが必須です。',
            'withdrawal_day1.numeric' => '口座引落日１が１から３１までに入力してください。',
            'withdrawal_day1.min' => '口座引落日１が１から３１までに入力してください。',
            'withdrawal_day1.max' => '口座引落日１が１から３１までに入力してください。',
            'withdrawal_day2.numeric' => '口座引落日２が１から３１までに入力してください。',
            'withdrawal_day2.min' => '口座引落日２が１から３１までに入力してください。',
            'withdrawal_day2.max' => '口座引落日２が１から３１までに入力してください。',
            'withdrawal_day3.numeric' => '口座引落日３が１から３１までに入力してください。',
            'withdrawal_day3.min' => '口座引落日３が１から３１までに入力してください。',
            'withdrawal_day3.max' => '口座引落日３が１から３１までに入力してください。',
            'withdrawal_day4.numeric' => '口座引落日４が１から３１までに入力してください。',
            'withdrawal_day4.min' => '口座引落日４が１から３１までに入力してください。',
            'withdrawal_day4.max' => '口座引落日４が１から３１までに入力してください。',
            'withdrawal_day5.numeric' => '口座引落日５が１から３１までに入力してください。',
            'withdrawal_day5.min' => '口座引落日５が１から３１までに入力してください。',
            'withdrawal_day5.max' => '口座引落日５が１から３１までに入力してください。',
        ];

//         $validator = $this->validate($request, $rules, $messages);
            $validator = Validator::make(request()->all(), $rules, $messages);

        // consignor_name 半角英数カナ文字チェック
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
//            if(!mb_ereg($this->_hankakumojiRegix, $request->agency_name)) {
//                $validator->errors()->add('agency_name', '収納代行会社名は半角文字のみ入力してください。');
//                return redirect("/admin/payment-agency/create")->withInput()->withErrors($validator->errors());
//            }
        }

        /*if(!mb_ereg($this->_hankakumojiRegix, $request->agency_name)) {
            $message = [
                'message'    => "収納代行会社名は半角文字のみ入力してください。",
                'alert-type' => 'error'
            ];
            session()->push('message', $message);
            return redirect("/admin/payment-agency/create");
        }*/

        $data = $this->insertUpdateDataWithoutValidation($request, $slug, $dataType->addRows, new $dataType->model_name());

        $message = array(
            'message'    => "「{$dataType->display_name_singular}」が登録されました。",
            'alert-type' => 'success'
        );

        return redirect()
            ->route("voyager.{$dataType->slug}.edit", ['id' => $data->id])
            ->with([
                'message'    => $message
            ]);
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $this->clearOldInput();

        // dd($request);
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $message = array(
            'message'    => "「{$dataType->display_name_singular}」が変更されました。",
            'alert-type' => 'success'
        );

        return redirect()
            ->route("voyager.{$dataType->slug}.edit", ['id' => $id])
            ->with([
                'message'    => $message
            ]);
    }

    private function clearOldInput() {
        if (session ()->has('_old_input')) {
            session()->forget ('_old_input');
        }
    }
}
