@extends('mail.layout', ['email_title' => "Password Reset"])
@section('content')
<!--[if IE]><div class="ie-container"><![endif]-->
<!--[if mso]><div class="mso-container"><![endif]-->
<table id="u_body" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #f9f9f9;width:100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f9f9f9;"><![endif]-->
            <div class="u-row-container" style="padding: 0px;background-color: transparent">
                <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
                    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                        <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                        <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                            <div style="height: 100%;width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!--><div style="height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->

                                    <taBitte Please ignore this email if you have not requested a password change. style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="overflow-wrap:break-word;word-break:break-word;padding:40px 40px 30px;font-family:'Lato',sans-serif;" align="left">

                                                <div style="line-height: 140%; text-align: left; word-wrap: break-word;">
                                                    <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 18px; line-height: 25.2px; color: #666666;">Hello {{$receiver_name}},</span></p>
                                                    <p style="font-size: 14px; line-height: 140%;">&nbsp;</p>
                                                    @if(isset($link))
                                                        <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 18px; line-height: 25.2px; color: #666666;">You have requested a password reset.</span></p>
                                                    @else
                                                        <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 18px; line-height: 25.2px; color: #666666;">Ihr Passwort-Bestätigungscode:</span></p>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="overflow-wrap:break-word;word-break:break-word;padding:0px 40px;font-family:'Lato',sans-serif;" align="left">
                                                @if(isset($link))
                                                    <div align="left">
                                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Lato',sans-serif;"><tr><td style="font-family:'Lato',sans-serif;" align="left"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:51px; v-text-anchor:middle; width:205px;" arcsize="2%" stroke="f" fillcolor="#18163a"><w:anchorlock/><center style="color:#FFFFFF;font-family:'Lato',sans-serif;"><![endif]-->
                                                        <a  style="box-sizing: border-box;display: inline-block;font-family:'Lato',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #133984; border-radius: 1px;-webkit-border-radius: 1px; -moz-border-radius: 1px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
                                                            <span style="display:block;padding:15px 40px;line-height:120%;"><span style="font-size: 18px; line-height: 21.6px;">{{$link}}</span></span>
                                                        </a>
                                                        <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                                                    </div>
                                                @endif
                                                @if(isset($otp_code))
                                                    <div align="left">
                                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Lato',sans-serif;"><tr><td style="font-family:'Lato',sans-serif;" align="left"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:51px; v-text-anchor:middle; width:205px;" arcsize="2%" stroke="f" fillcolor="#18163a"><w:anchorlock/><center style="color:#FFFFFF;font-family:'Lato',sans-serif;"><![endif]-->
                                                        <a style="box-sizing: border-box;display: inline-block;font-family:'Lato',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #133984; border-radius: 1px;-webkit-border-radius: 1px; -moz-border-radius: 1px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
                                                            <span style="display:block;padding:15px 40px;line-height:120%;"><span style="font-size: 18px; line-height: 21.6px;">{{$otp_code}}</span></span>
                                                        </a>
                                                        <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="overflow-wrap:break-word;word-break:break-word;padding:40px 40px 30px;font-family:'Lato',sans-serif;" align="left">

                                                <div style="line-height: 140%; text-align: left; word-wrap: break-word;">
                                                    <p style="font-size: 14px; line-height: 140%;"><span style="color: #888888; font-size: 14px; line-height: 19.6px;"><em><span style="font-size: 16px; line-height: 22.4px;">Bitte ignorieren Sie diese E-Mail, wenn Sie keine Passwortänderung angefordert haben.</span></em></span><br /><span style="color: #888888; font-size: 14px; line-height: 19.6px;"><em><span style="font-size: 16px; line-height: 22.4px;">&nbsp;</span></em></span></p>
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td><![endif]-->
                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>
            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        </td>
    </tr>
    </tbody>
</table>
<!--[if mso]></div><![endif]-->
<!--[if IE]></div><![endif]-->
@endsection
