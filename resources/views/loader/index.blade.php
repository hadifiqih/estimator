<!DOCTYPE html>
<html>
<head>
    <title>Redirect Countdown</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .lds-circle {
        display: inline-block;
        transform: translateZ(1px);
        }
        .lds-circle > div {
        display: inline-block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        background: rgb(255, 200, 0);
        animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        @keyframes lds-circle {
        0%, 100% {
            animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
        }
        0% {
            transform: rotateY(0deg);
        }
        50% {
            transform: rotateY(1800deg);
            animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
        }
        100% {
            transform: rotateY(3600deg);
        }
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="mt-5 mb-5">
                <div class="lds-circle"><div></div></div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col mt-5">
                <h1 class="text-warning">Anda akan dialihkan</h1>
                <h1 class="text-danger">dalam <span id="countdown">5</span> detik.</h1>
            </div>
        </div>
        {{-- Button menuju halaman yang diredirect --}}
        <div class="row align-items-center">
            <div class="col mt-5">
                <a href="{{ $url }}" class="btn btn-primary">Beralih ke Antrian</a>
            </div>
      </div>
    <script>
        $(document).ready(function() {
            var countdown = 5;

            function updateCountdown() {
                $('#countdown').text(countdown);
                countdown--;

                if (countdown < 0) {
                    window.location.href = "{{ $url }}";
                } else {
                    setTimeout(updateCountdown, 1000);
                }
            }
            updateCountdown();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>


</html>
