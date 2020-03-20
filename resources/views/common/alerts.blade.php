<link rel="stylesheet" href="{{asset('css/alert.css')}}">

<div id="alertDiv" class="backBlack alertBackBlack">
    <div id="alertMainBody" class="alertMainBody">
        <div class="alertTopCircle loadingCircle">
            <i id="alertSuccessIcon" class="fas fa-check" style="display: none"></i>
            <i id="alertDangerIcon" class="fas fa-times" style="display: none"></i>
            <img id="alertLoading" src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%;" >
        </div>
        <div id="alertMsg" class="alertMsg"></div>
        <div id="alertButton" class="alertButton successButton" style="display: none" onclick="closeAlert()">
            ok
        </div>
    </div>
</div>

<script>
    var callBackFunction = 0;
    function openLoading(){
        callBackFunction = 0;
        $('#alertDiv').css('display', 'flex');
    }

    function resultLoading(_text, _kind, _callBack){
        $('#alertLoading').fadeToggle(function(){
            $('#alertSuccessIcon').parent().removeClass('loadingCircle');

            if(_kind == 'success') {
                $("#alertButton").addClass('successButton');
                $('#alertSuccessIcon').fadeToggle(1000);
            }
            else {
                $("#alertButton").addClass('dangerButton');
                $('#alertSuccessIcon').parent().addClass('dangerButton');
                $('#alertDangerIcon').fadeToggle(1000);
            }

            $('#alertMainBody').css('height', '250px');
            $('#alertButton').fadeToggle();

            $('#alertMsg').html(_text);

            if(typeof _callBack == "function")
                callBackFunction = _callBack;
        });

    }

    function closeAlert(){
        $('#alertMsg').text('');
        $('#alertLoading').fadeToggle();
        $('#alertSuccessIcon').parent().addClass('loadingCircle');
        $('#alertSuccessIcon').parent().removeClass('dangerButton');
        $('#alertSuccessIcon').parent().removeClass('successButton');

        $("#alertButton").removeClass('dangerButton');
        $("#alertButton").removeClass('successButton');


        $('#alertSuccessIcon').hide();
        $('#alertDangerIcon').hide();
        $('#alertMainBody').css('height', '0px');
        $('#alertButton').hide();

        $('#alertDiv').hide();

        if(callBackFunction != 0)
            callBackFunction();
    }
</script>