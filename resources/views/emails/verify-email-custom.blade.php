<!-- Change values in the template and pass { {variables} } with API call -->
<!-- Feel free to adjust it to your needs and delete all these comments-->
<!-- Also adapt TXT version of this email -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title></title>
    <!--[if !mso]><!-- -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass * {
            line-height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
    </style>
    <!--[if !mso]><!-->
    <style type="text/css">
        @media only screen and (max-width:480px) {
            @-ms-viewport {
                width: 320px;
            }

            @viewport {
                width: 320px;
            }
        }
    </style>
    <!--<![endif]-->
    <!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]-->
    <!--[if lte mso 11]><style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style><![endif]-->
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"
        type="text/css">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
    </style>
    <!--<![endif]-->
    <style type="text/css">
        @media only screen and (max-width:595px) {
            .container {
                width: 100% !important;
            }

            .button {
                display: block !important;
                width: auto !important;
            }
        }
    </style>
</head>

<body style="font-family: 'Inter', sans-serif; background: #E5E5E5;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#F6FAFB">
        <tbody>
            <tr>
                <td valign="top" align="center">
                    <table class="container" width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>

                            <tr>
                                <td class="main-content" style="padding: 48px 30px 40px; color: #000000;"
                                    bgcolor="#ffffff">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td
                                                    style="padding: 0 0 24px 0; font-size: 24px; line-height: 150%; font-weight: bold; color: #000000; letter-spacing: 0.01em; text-align: center;">
                                                    GBILLS247
                                                </td>
                                            </tr>


                                            <tr>
                                                <td
                                                    style="padding: 0 0 24px 0; font-size: 18px; line-height: 150%; font-weight: bold; color: #000000; letter-spacing: 0.01em;">
                                                    Dear {{$user->name}}!
                                                </td>
                                            </tr>
                                            <br>

                                            <br>
                                            <br>
                                            <tr>
                                                <td
                                                    style="padding: 0 0 10px 0; font-size: 14px; line-height: 150%; font-weight: 400; color: #000000; letter-spacing: 0.01em;">
                                                    Welcome to GBILLS247
                                                </td>
                                            </tr>
                                            <br>

                                            <br>
                                            <tr>
                                                <td
                                                    style="padding: 0 0 16px 0; font-size: 14px; line-height: 150%; font-weight: 400; color: #000000; letter-spacing: 0.01em;">
                                                    <div style="font-weight: bold;">Additional Information</div>
                                                    You are to verify your email using this
                                                    verification link below
                                                </td>
                                            </tr>



                                            <tr>
                                                <td style="padding: 0 0 24px 0;">
                                                    <a class="button" href="{{$url}}" title="Verify email"
                                                        style="width: 100%; background: #4C83EE; text-decoration: none; display: inline-block; padding: 10px 0; color: #fff; font-size: 14px; line-height: 21px; text-align: center; font-weight: bold; border-radius: 7px;">Verify
                                                        Email</a>
                                                </td>
                                            </tr>
                                            <br>

                                            <tr>
                                                <td
                                                    style="padding: 0 0 16px 0; font-size: 14px; line-height: 150%; font-weight: 400; color: #000000; letter-spacing: 0.01em;">
                                                    If you have issues with the button above click this: <a
                                                        href="{{$url}}">{{$url}}</a>
                                                </td>
                                            </tr>







                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>