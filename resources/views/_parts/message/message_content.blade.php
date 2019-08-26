@if (isset($parent_list))
<div class="panel-parent">
    <span>コピー元</span>
    <select name="parent_id" class="form-control" style="width: 40%; display: inline-block;">
        @foreach ($parent_list as $key=>$val)
            <option value="{{$val->id}}">{{$val->message_file_name}}</option>
        @endforeach
    </select>
    <button type="button" class="btn btn-copy" id="btn-copy-message">コピー</button>
</div>
    
@endif
<hr>
<div class="panel-screen-content">
    <span>画面名</span>
    <ul id="screen-name">
        @foreach ($screen_list as $key=>$val)
            <li id="{{$val->screen_key}}">{{$val->screen_value}}</li>
        @endforeach
    </ul>
</div><!-- screen list -->
{{--<div class="panel-screen-content">
    <input type="button" name="" class="btn" value="編集">
    <input type="button" name="" class="btn" value="追加">

</div>--}}

<input type="hidden" name="message_list" value="">
<input type="hidden" name="screen_list" value="">
<hr>
<div class="panel-message-content">
    <span>画面内メッセージ</span>
    <ul>
        <li>メッセージキー</li>
        <li>メッセージテキスト</li>
        <li>メッセージコメント</li>
    </ul>
    
    @foreach ($message_list as $key=>$val)
        <div class="{{$val->screen_key}}">
        <input type="text" class="form-control" id="message_key|{{$val->id}}" value="{{$val->message_key}}" readonly="readonly">
        <input type="text" class="form-control" id="message_value|{{$val->id}}" value="{{$val->message_value}}">
        <input type="text" class="form-control" id="comment|{{$val->id}}" value="{{$val->comment}}">

        </div>
    @endforeach
</div>

{{--<div id="dialog-edit-screen">
    <form>
        <table class="screen-edit-tbl">
            <tr>
                <td colspan="2">
                    <select class="form-control select-screen">
                        @foreach ($screen_list as $key=>$val)
                        <option value="{{$val->id}}">{{$val->screen_value}}</option>
                        @endforeach 
                    </select>
                </td>
            </tr>
            @foreach ($screen_list as $key=>$val)
            <tr class="{{$val->id}} screen-area">
                <td>画面名メッセージキー（key）</td>
                <td><input type="text" name="" readonly="readonly" value="{{$val->screen_key}}"></td>
            </tr>
            <tr class="{{$val->id}} screen-area">
                <td>画面名メッセージ表示値（value）</td>
                <td><input type="text" name="screen-edit[{{$val->id}}]" value="{{$val->screen_value}}"></td>
            </tr>
            @endforeach 
        </table>
    </form>
</div> --}}

@section('javascript')
<script type="text/javascript">
var message_list = {!! json_encode($message_list) !!};
var screen_list = {!! json_encode($screen_list) !!};
$(function() {
        start_all_js ();
});

// trying to separate for pschool call
function start_all_js() {
    $('#screen-name li').click(function() {
            var screen_key_arr = $(this).attr('id').split('.');

            if (screen_key_arr[0] && screen_key_arr[1]) {
                var screen_key = screen_key_arr[0]+'\\.'+screen_key_arr[1];
                $('.panel-message-content div').hide();
                $('.panel-message-content div.'+screen_key).show();
            }
            // li: add class active
            $('#screen-name li.active').removeClass('active');
            $(this).addClass('active', true);

         
        });
        // default Home Screen is selected
        $('#screen-name li').first().click();
        
        // comfortable: press enter when change message content
        $('.panel-message-content div input').keypress(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
              if (keycode == '13') {
                
              }
        });

        $('.panel-message-content div input').change(function() {
            var input_id = $(this).attr('id').split('|');

            var idx = message_list.hasOwnProperty(input_id[1]);
            if (idx){
                
                message_list[input_id[1]][input_id[0]] = $(this).val();
            }
            
        });
        
        $('.save').click(function() {
            
            $("[name=screen_list]").val(JSON.stringify(screen_list));
            $("[name=message_list]").val(JSON.stringify(message_list));
            return true;
        });
        
    @if (isset($parent_list))    
    // Call ajax to store session
    // function store_message_content() {
    //     $("[name=screen_list]").val(JSON.stringify(screen_list));
    //     $("[name=message_list]").val(JSON.stringify(message_list));

    //     var url = '/admin/pschool/storeupdatedmessage';
    //     var lang_code = $('[name=language]').val();
    //     var message_file = $('[name=message_file]').val();
    //     var business_type_id = $('[name=business_type_id]').val().split('|');
    //         $.ajax({
    //             type: "POST",
    //             data:{  
    //                     screen_list : JSON.stringify(screen_list),
    //                     message_list : JSON.stringify(message_list),
    //                     lang_code : lang_code,
    //                     message_file_name : message_file,
    //                     bussiness_type_id : business_type_id[0]
    //                     },
    //             dataType: "text",
    //             url: url,
    //             contentType: "application/x-www-form-urlencoded",
    //             success: function(data){
    //                 console.log(data);
    //                 return true;
                    
    //             },
    //             error: function(data) {
    //                 console.log("error");
    //             },
    //         });

        
    //     return true;
    //     }

    // Button to change parent_id
    $('#btn-copy-message').click(function() {
        
        var message_file_id = $('[name=parent_id]').val();
        var business_type_id = $('[name=business_type_id]').val().split('|');

        var lang_code = $('[name=language]').val();
        var pschool_id = $('[name=pschool_id]').val();
        if (message_file_id == null) {
            return;
        }
        $('#voyager-loader').show();
        var url = '/admin/pschool/loadscreen';
        $.ajax({
            type: "GET",
            data:{  message_file_id: message_file_id, 
                    lang_code : lang_code,
                    pschool_id : pschool_id,
                    business_type_id : business_type_id[0]
                    },
            dataType: "html",
            url: url,
            contentType: "application/x-www-form-urlencoded",
            success: function(data){
                $('#voyager-loader').hide();
                $('#custom-screen-area').html(data);
                
            },
            error: function(data) {
                $('#voyager-loader').hide();
                console.log("error");
            },
        });
    });
    
    @endif
}
@if (isset($parent_list))  
var message_content_clone;
function clone_message_content() {
    message_content_clone = jQuery.extend(true, {}, message_list);
}

function reverse_message_content() {
    message_list = jQuery.extend(true, {}, message_content_clone);

}
@endif
</script> 