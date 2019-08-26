{{--支払方法--}}
<p style="margin-bottom: 10px">
    @if($data['canMerge']==true)
        <p id="merge_invoice">
            <label><input type="radio" name="is_merge_invoice" value = "0" checked>個別に支払う</label>
            <label><input type="radio" name="is_merge_invoice" value = "1">定期請求書に含めて支払う</label><br/>
        </p><br/>
    @else
        <input type="hidden" name = "is_merge_invoice" value="0">
    @endif
    @if (count($payment_methods) > 0)
        <div id ="unmerge_method">
        支払方法&nbsp;&nbsp;&nbsp;&nbsp;
        @foreach($payment_methods as $key => $payment)
            <label><input type="radio" name="payment_method" value="{{$key}}" @if ($loop->index == 0 || \App\Common\Constants::$PAYMENT_TYPE[$key] == $parent['invoice_type']) checked @endif /> {{$payment}}</label>&nbsp;&nbsp;
        @endforeach
        </div>
        <div id ="merge_method" style="display: none">
            支払方法&nbsp;&nbsp;&nbsp;&nbsp;
            @foreach($merge_method as $key => $payment)
                <label><input type="radio" name="payment_method_merge" value="{{$key}}" @if ($loop->index == 0) checked @endif /> {{$payment}}</label>&nbsp;&nbsp;
            @endforeach
        </div>
    @else
        <input type="hidden" name="payment_method" value="CASH"/>
    @endif
</p>
<script>
    $('input[name=is_merge_invoice]').change();
    $(document).on('change','input[name=is_merge_invoice]',function(){
        var merge = $('input[name=is_merge_invoice]:checked').val();
       console.log(merge);
       if(merge==1){
           $("#merge_method").show();
           $("#unmerge_method").hide();
       }else{
           $("#merge_method").hide();
           $("#unmerge_method").show();
       }
    })
</script>