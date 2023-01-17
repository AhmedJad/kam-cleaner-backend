<div>
    <h1>Reset Password</h1>
    <p>Welcome , {{ $data['user']->first_name }}</p>
    <a href="{{env('UI_URL')}}/reset-password/{{$data['token']}}">Click here to go to reset password form</a>
</div>