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
    <div class="container shadow-lg p-3 mx-1 my-3 rounded d-flex justify-content-center w-75">
        <form onsubmit="return false">
            <div id="timer"></div>
            <label for="OTP" class="required">OTP</label>
            {{-- otp has to be 6 numbers-long --}}
            <input id="OTP" type="number" name="OTP" class="form-control" min='6' required>
            <div>
                <label for="period">اختر مدة الاشتراك</label><br>

                <input type="radio" id="30" name="period" value="30" required checked>
                <label for="30">30 يوما </label><br> {{--({{getenv('MONTH_CHARGE')}}$)--}}
                <input type="radio" id="365" name="period" value="365" required>
                <label for="365">365 يوما </label><br>{{--({{getenv('YEAR_CHARGE')}}$)--}}

            </div>
                <div class="d-inline-block">
                <button id="verifyOTP" class="btn btn-primary m-2" >الاشتراك</button>
                <button id="resendOTP" class="btn btn-secondary m-2" type="button" >اعادة ارسال الرمز</button>
            </div>
        </form>
    </div>
</div>
<script>
    var expirationTime = parseInt({{$subscriber->otp_expiry}} - (new Date().getTime()/1000));
    var interval;
    function startCountdown() {
        $('#resendOTP').prop("disabled", true);
        interval = setInterval(function() {
            var seconds = expirationTime--;
            document.getElementById("timer").innerHTML = seconds + " الثواني المتبقية ";
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
        $.ajax({
                url: '/resendOTP',
                type: 'POST',
                data: { '_token': $('meta[name=csrf-token]').attr('content'), telephone: {{$subscriber->telephone}}, country_code: {{$subscriber->country_code}} }
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
        const periodRadios = document.querySelectorAll('input[name="period"]');
        let selectedPeriod;
        for (const radio of periodRadios) {
            if (radio.checked) {
                selectedPeriod = radio.value;
                break;
            }
        }
        if (otp.length == 6) {
        $.ajax({
                url: '/enteredOTP',
                type: 'POST',
                data: { '_token': $('meta[name=csrf-token]').attr('content'), OTP: otp, telephone: {{$subscriber->telephone}}, country_code: {{$subscriber->country_code}}, period: selectedPeriod }
            })
            .done(function(data) {
                decodedData = JSON.parse(data);
                showSnackbar(decodedData.message);
                if (decodedData.error == false) {
                    $('#verifyOTP').prop("disabled", true);
                    setTimeout(() => window.location.href = "/charge?period=" + selectedPeriod, 2000); 
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