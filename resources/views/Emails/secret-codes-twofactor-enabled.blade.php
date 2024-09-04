<p>Hi Sir/Ma'am</p>

<p>Two factor authentication has been enabled for your account. Your secret codes are:</p>
<ul>
@foreach ( $data['codes'] as $code )
<li>{{$code}}</li>
@endforeach
</ul>
<p>Please don't share your secret codes to anyone</p>

<p>This is an automated email, please do not reply.</p>