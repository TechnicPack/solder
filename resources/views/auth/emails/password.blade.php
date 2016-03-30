<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic" rel="stylesheet">
</head>
<body style="background-color:#ecf0f1;">
<table style="background-color:#ffffff;border-collapse:collapse;border-spacing:0;box-sizing:border-box;p;margin:auto;width:580px">
<tr>
<td style="padding:15px;">
    <table style="width:100%;">
        <tbody>
            <tr style="box-sizing:border-box">
                <td style="box-sizing:border-box;padding:0;text-align:center">
                    <img alt="Technic-logo" class="logo" height="70" src="{{ URL::asset('img/wrenchIcon.svg') }}" style="border:0;box-sizing:border-box;vertical-align:middle">
                    <h1 style="box-sizing:border-box;color:inherit;font-family:Lato,'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:39px;font-weight:400;line-height:1.1;margin:.67em 0;margin-bottom:10.5px;margin-top:21px">Forgot your password?</h1>
                    <hr style="border:0;border-top:1px solid #ecf0f1;box-sizing:content-box;height:0;margin-bottom:21px;margin-top:21px">
                </td>
            </tr>
            <tr style="box-sizing:border-box">
                <td style="box-sizing:border-box;padding:0">
                    <p style="box-sizing:border-box;margin:0 0 10.5px">It happens. Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a></p>
                </td>
            </tr>
            <tr style="box-sizing:border-box">
                <td style="box-sizing:border-box;padding:0">
                    <hr style="border:0;border-top:1px solid #ecf0f1;box-sizing:content-box;height:0;margin-bottom:21px;margin-top:21px">
                    <p class="text-center" style="box-sizing:border-box;margin:0 0 10.5px;text-align:center"><a class="text-muted" href="http://technicpack.net/" style="background-color:transparent;box-sizing:border-box;color:#b4bcc2;text-decoration:underline">Powered by the Technic Platform</a></p>
                </td>
            </tr>
        </tbody>
    </table>
</td>
</tr>


</table>
</body></html>
