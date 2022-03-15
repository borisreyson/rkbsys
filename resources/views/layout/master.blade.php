<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<!-- <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://www.google.com"> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src * style-src * 'unsafe-inline'  script-src * img-src * data: 'unsafe-eval'" /> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self'"> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src https://*; child-src 'none';"> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://abpjobsite.com:3000 wss://abpjobsite.com:3000 https://abpjobsite.com "> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://www.google.com http://www.abpjobsite.com/  https://www.abpjobsite.com/  https://abpjobsite.com/ https://abpjobsite.com:3000 https://abpjobsite.com/logistic/rkbPrint https://cdn.datatables.net https://cdnjs.cloudflare.com https://cdn.datatables.net https://mtkjobsite.com"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <link rel="shortcut icon" href="{{asset('abp.png')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
	<meta name="csrf-token" content="{{csrf_token()}}">
    @yield('css')
</head>
@yield('content')
<script src="{{asset('/js/app.js')}}"></script>
<script>
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
	var csrfToken = $('[name="csrf_token"]').attr('content');

            setInterval(refreshToken, 3600000); // 1 hour 

            function refreshToken(){
                $.get('refresh-csrf').done(function(data){
                    csrfToken = data; // the new token
                });
                console.log(csrfToken);
            }

            setInterval(refreshToken, 3600000);
</script>
<script src="{{asset('/js/bootstrap-notify.js')}}"></script>
@yield('js')
<script>

    function refreshSession(){
                $.ajax({
                    type:"GET",
                    url:"{{'/test/node'}}",
                    success:function(res){
                        if(res=='null'){
                            alert("Login Anda Kadaluarsa, Silahkan Login Kembali!");

                            window.location.reload();
                        }
                    }

                });
                console.log("{{date('d F Y , H:i:s')}}")
            }
    setInterval(refreshSession, 3600000); 
</script>

</body>
</html>