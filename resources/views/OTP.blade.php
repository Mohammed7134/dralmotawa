@extends("layouts.app")
@section('title', 'الاشتراك')
@section('description', 'الاشتراك في خدمة رسائل الواتساب')
@section("content")

@if($errors->any())
<script>
    showSnackbar("{{$errors->all()[0]}}");
</script>
@endif

<div class="row justify-content-center">
    <p>الاشتراك في خدمة رسائل الواتساب اليومية</p>
    <div class="container shadow-lg p-3 mx-1 my-3 rounded d-flex justify-content-center w-50">
        <form onsubmit="return false">
            <div id="timer"></div>
            <label for="OTP" class="required">OTP</label>
            {{-- otp has to be 6 numbers-long --}}
            <input id="OTP" type="number" name="OTP" class="form-control" min='6' required>
            <div class="d-inline-block">
                <button id="verifyOTP" class="btn btn-primary m-2" >الاشتراك</button>
                <button id="resendOTP" class="btn btn-secondary m-2" type="button" >اعادة ارسال الرمز</button>
            </div>
        </form>
    </div>
</div>
<script>
    var expirationTime = 60;
    var interval;
    function startCountdown() {
        $('#resendOTP').prop("disabled", true);
        interval = setInterval(function() {
            var seconds = expirationTime--;
            document.getElementById("timer").innerHTML = seconds + "الثواني المتبقية";
            if (seconds <= 0) {
                clearInterval(interval);
                $('#resendOTP').prop("disabled", false);
                $('#verifyOTP').prop("disabled", true);
            }
        }, 1000);
    }
    function resetCountdown() {
        $('#verifyOTP').prop("disabled", false);
        clearInterval(interval);
        expirationTime = 60;
    }
    startCountdown();
    $('#resendOTP').click(function() {
        let data = {
            '_token': $('meta[name=csrf-token]').attr('content'),
            telephone: {{$telephone}}
        }
        $.ajax({
                url: '/resendOTP',
                type: 'post',
                data: { '_token': $('meta[name=csrf-token]').attr('content'), telephone: {{$telephone}} }
            })
            .done(function(data) {
                decodedData = JSON.parse(data);
                showSnackbar(decodedData.message);
                if (decodedData.error == false) {
                    resetCountdown();
                    startCountdown();
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                showSnackbar(thrownError);
            });
    });
    $('#verifyOTP').click(function() {
        const otp = document.getElementById('OTP').value;
        if (otp.length == 6) {
        $.ajax({
                url: '/enteredOTP',
                type: 'post',
                data: { '_token': $('meta[name=csrf-token]').attr('content'), OTP: otp, telephone: {{$telephone}} }
            })
            .done(function(data) {
                decodedData = JSON.parse(data);
                showSnackbar(decodedData.message);
                if (decodedData.error == false) {
                    $('#verifyOTP').prop("disabled", true);
                    setTimeout(() => window.location.href = "/", 2000); 
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                showSnackbar(thrownError);
            });
        } else {
            showSnackbar('أدخل الرمز المكون من ٦ أرقام');
        }
    });
</script>
@endsection