<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tax Forms Submitted</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 15px; color: #333; background-color: #f8f9fa; margin: 0; padding: 20px;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px;">
    <tr>
        <td style="padding: 20px;">
            <h2 style="color: #0056b3; font-size: 22px; margin-top: 0;">Tax Forms Submitted</h2>

            <p style="line-height: 1.6;">Hello Manager,</p>

            <p style="line-height: 1.6;">
                User <strong>{{ $tax->user->name }}</strong>
                (<a href="mailto:{{ $tax->user->email }}">{{ $tax->user->email }}</a>)
                has submitted Tax Return ID <strong>#{{ $tax->id }}</strong>.
            </p>

            <p style="line-height: 1.6;">The following form PDFs are available for download:</p>

            <ul style="line-height: 1.8;">
                @if($basicInfoPdf)
                    <li><a href="{{ $basicInfoPdf }}" style="color: #0056b3;">Basic Info Form Data</a></li>
                @endif
                @if($otherPdf)
                    <li><a href="{{ $otherPdf }}" style="color: #0056b3;">Other Form Data</a></li>
                @endif
                @if($incomePdf)
                    <li><a href="{{ $incomePdf }}" style="color: #0056b3;">Income Form Data</a></li>
                @endif
                @if($deductionPdf)
                    <li><a href="{{ $deductionPdf }}" style="color: #0056b3;">Deduction Form Data</a></li>
                @endif
            </ul>

            <p style="line-height: 1.6; margin-top: 30px;">
                Best regards,<br>
                <strong>Your Application</strong>
            </p>
        </td>
    </tr>
</table>

</body>
</html>
