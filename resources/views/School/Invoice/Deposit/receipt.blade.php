<style type='text/css'>
    body {
        font-family: "ipag";
    }
</style>

<div style="margin: auto; border: 1px solid black; page-break-after: always;">
    <div style="border-bottom: 2px solid black; width: 158px; text-align: center; margin: 25px auto; font-size: 35px; font-weight: bold">
        領 収 書
    </div>

    <div style="width: 640px; margin: auto">
        <div style="float: right; margin-right: 50px; margin-bottom: 20px; font-size: 13px">
            <div>No.{{array_get($invoice, 'receipt_no')}}</div>
            <div>{{Carbon\Carbon::parse()->format('Y年m月d日')}}</div>
        </div>
        <div style="clear: both"></div>

        <p>{{str_replace('　', '', (array_get($invoice, 'parent_name')))}}　様</p>
        <div style="width: 100%; height: 50px; line-height: 44px; font-size: 35px; font-weight: bold; background-color: lightgray;">
            <span style="margin-left: 71px">￥{{number_format(array_get($invoice, 'amount', 0))}}</span>
        </div>
        <div style="margin-left: 30px; margin-top: 10px; word-wrap: break-word; font-size: 12px">但: {{array_get($invoice, 'proviso')}}</div>

        <div style="float: right; width: 280px; margin-top: 50px; margin-bottom: 50px; position: relative">
            <div style="font-size: 12px">
                〒{{substr(session()->get('school.login.zip_code'), 0, 3)}}-{{substr(session()->get('school.login.zip_code'), 3, 4)}}<br>
                {{session()->get('school.login.pref_name')}}{{session()->get('school.login.city_name')}}{{session()->get('school.login.address')}}<br>
                {{session()->get('school.login.building')}}<br>
                {{session()->get('school.login.name')}}<br>
                TEL {{session()->get('school.login.tel')}}　FAX {{session()->get('school.login.fax')}}<br>
                {{session()->get('school.login.mailaddress')}}<br>
                {{session()->get('school.login.official_position')}} {{session()->get('school.login.daihyou')}}<br>
                @if(session()->get('school.login.auth_type') != 1 && session()->get('school.login.auth_type') != 2)
                    担当：{{session()->get('school.login.name')}}
                @endif
            </div>

            @if (session()->get('school.login.kakuin_path'))
            <img style="height: 60px; width: 60px; position: absolute; top: 35; right: 0" src="{{request()->root()}}{{session()->get('school.login.kakuin_path')}}">
            @endif
        </div>
        <div style="clear: both"></div>
    </div>
</div>