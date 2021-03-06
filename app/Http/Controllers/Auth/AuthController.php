<?php

namespace Katsitu\Http\Controllers\Auth;

use Katsitu\Contracts\Progress;
use Katsitu\Http\Requests\ForgotPasswordRequest;
use Katsitu\Http\Requests\PasswordResetRequest;
use Katsitu\PasswordReset;
use Katsitu\Services\FaizMailer;
use Katsitu\User;
use Illuminate\Http\Request;
use Mail;
use Validator;
use Katsitu\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    protected $redirectPath = '/profile';

    protected $redirectTo = '/profile';

    protected $loginPath = '/login';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    private $request;
    private $mail;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Mail $mail)
    {
        $this->middleware('guest', ['except' => ['getLogout', 'confirmEmail', 'sendVerification']]);
        $this->request = $request;
        $this->mail = $mail;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|alpha_num|min:3|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        User::unguard();
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activity' => serialize(['register']),
            'confirmation_code' => str_random(32)
        ]);
        User::reguard();

        $this->sendVerification($user, new FaizMailer, false);

        flash()->success('Thanks for signing up! Please check your email.');

        return $user;
    }

    public function confirmEmail(User $user, Progress $progress, $confirmationCode)
    {

        if( !$confirmationCode ) {
            throw new \Exception;
        }

        $user = $user->where('confirmation_code', $confirmationCode)->first();

        if (!$user) {
            abort(404);
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        $progress->updateProgress($user);

        flash()->success('You have successfully verified your email.');
        return redirect()->route('profile', $user->username);
    }

    public function sendVerification(User $user, FaizMailer $mailer, $redirect = true)
    {
        if(!$user->exists) $user = $this->request->user();

        $mailer->sendVerificationEmail($user);

        if($redirect) {
            flash()->message('Your verification email has been resend. Please check your email.');
            return redirect()->back();
        }
    }

    // Ask user to put their email
    public function getForgotPassword()
    {
        return view('auth/forgotpassword');
    }

    // Process their email
    public function postForgotPassword(ForgotPasswordRequest $request, FaizMailer $mailer, User $user, PasswordReset $passwordReset)
    {
        $data = $request->all();

        // Get user of the email
        $user = $user->where('email', $data['email'])->firstOrFail();

        // Check if the user has reset token already, and delete if it has
        if($user->passwordReset)
            $user->passwordReset->delete();

        // Generate new reset token
        $passwordReset->token = str_random(16);

        // Save to token
        $user->passwordReset()->save($passwordReset);

        // Send email
        $mailer->sendPasswordReset($user->fresh());

        return view('auth/sentforgotpassword');
    }

    // Allow user to change their password
    public function passwordReset(PasswordReset $passwordReset)
    {
        $date = $passwordReset->created_at;

        // If passwordreset token is 3 days old, delete it and return not found
        if($date->diffInDays($date->now(), false) > 3) {
            $passwordReset->delete();
            abort(404);
        }

        return view('auth/passwordreset', compact('passwordReset'));
    }

    public function postPasswordReset(PasswordResetRequest $request, PasswordReset $passwordReset)
    {
        $user = $passwordReset->user;
        $user->password = bcrypt( $request->get('password') );
        $user->save();

        // Remove passwordreset
        $passwordReset->delete();

        return view('auth/successpasswordreset');
    }
}
