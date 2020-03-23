<link rel="stylesheet" href="{{asset('css/alert.css')}}">

<style>
    .twoButton{
        align-items: center;
        justify-content: space-around;
    }
    .oneButton{
        align-items: center;
        justify-content: center;
    }
</style>

<div id="alertDiv" class="backBlack alertBackBlack">
    <div id="alertMainBody" class="alertMainBody">
        <div class="alertTopCircle loadingCircle">
            <i id="alertSuccessIcon" class="fas fa-check" style="display: none"></i>
            <i id="alertDangerIcon" class="fas fa-times" style="display: none"></i>
            <i id="alertWarningIcon" style="display: none">!</i>
            <img id="alertLoading" src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%;" >
        </div>
        <div id="alertMsg" class="alertMsg"></div>
        <div style="width: 100%; display: flex">
            <div id="alertButton" class="alertButton successButton" style="display: none" onclick="closeAlert(1)">
                ok
            </div>
            <div id="alertButton2" class="alertButton successButton" style="display: none" onclick="closeAlert(0)">
                cancel
            </div>
        </div>
    </div>
</div>

<script>

    var callBackFunction = 0;
    function openLoading(){
        callBackFunction = 0;
        $('#alertDiv').css('display', 'flex');
    }

    function resultLoading(_text, _kind, _callBack, ){
        $('#alertLoading').fadeToggle(function(){
            $('#alertSuccessIcon').parent().removeClass('loadingCircle');


            if(_kind == 'success') {
                $("#alertButton").parent().addClass('oneButton');
                $("#alertButton").addClass('successButton');
                $('#alertSuccessIcon').fadeToggle(1000);
            }
            else if(_kind == 'warning'){
                $("#alertButton").parent().addClass('twoButton');

                $("#alertButton").addClass('warningOk');
                $("#alertButton2").addClass('warningCancel');
                $('#alertButton2').fadeToggle();
                $('#alertSuccessIcon').parent().addClass('warningButton');
                $('#alertWarningIcon').fadeToggle(1000);
            }
            else {
                $("#alertButton").parent().addClass('oneButton');
                $("#alertButton").addClass('dangerButton');
                $('#alertSuccessIcon').parent().addClass('dangerButton');
                $('#alertDangerIcon').fadeToggle(1000);
            }

            $('#alertMainBody').css('max-height', '3000px');
            $('#alertButton').fadeToggle();

            $('#alertMsg').html(_text);

            if(typeof _callBack == "function")
                callBackFunction = _callBack;
        });

    }

    function closeAlert(_kind){
        $('#alertMsg').text('');
        $('#alertLoading').fadeToggle();
        $('#alertSuccessIcon').parent().addClass('loadingCircle');
        $('#alertSuccessIcon').parent().removeClass('dangerButton');
        $('#alertSuccessIcon').parent().removeClass('successButton');
        $('#alertSuccessIcon').parent().removeClass('warningButton');

        $("#alertButton").removeClass('dangerButton');
        $("#alertButton").removeClass('successButton');
        $("#alertButton").removeClass('warningOk');
        $("#alertButton2").removeClass('warningCancel');
        $('#alertButton2').hide();


        $('#alertSuccessIcon').hide();
        $('#alertDangerIcon').hide();
        $('#alertWarningIcon').hide();
        $('#alertMainBody').css('max-height', '0px');
        $('#alertButton').hide();

        $('#alertDiv').hide();

        $("#alertButton").parent().removeClass('oneButton');
        $("#alertButton").parent().removeClass('twoButton');

        if(_kind == 1 && callBackFunction != 0)
            callBackFunction();
    }
</script>
