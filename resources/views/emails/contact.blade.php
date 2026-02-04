<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
    <h2>New contact form submission</h2>

    <p><strong>Name:</strong> {{ ($data['f_name'] ?? '') }} {{ ($data['l_name'] ?? '') }}</p>
    <p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>
    <p><strong>Subject:</strong> {{ $data['subject'] ?? '' }}</p>

    <h3>Message</h3>
    <p>{!! nl2br(e($data['message'] ?? '')) !!}</p>

</body>
</html>
