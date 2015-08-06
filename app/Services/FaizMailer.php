<?php


namespace Katsitu\Services;


use Illuminate\Support\Facades\Mail;
use Katsitu\User;

class FaizMailer {

    public function sendVerificationEmail(User $user)
    {
        Mail::send('email.verify', ['user'=>$user], function($m) use ($user) {
            $m->from(config('katsitu.emails.noreply.address'), config('katsitu.emails.noreply.name'));
            $m->to($user->email, $user->name)->subject('Verify your email address at Katsitu.Com');
        });
    }

    public function sendPasswordReset(User $user)
    {
        Mail::send('email.passwordreset', ['user' => $user], function($m) use ($user) {
            $m->from(config('katsitu.emails.noreply.address'), config('katsitu.emails.noreply.name'));
            $m->to($user->email, $user->name)->subject('Password Reset at Katsitu.Com');
        });
    }
}