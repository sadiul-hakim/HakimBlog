<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Login'
        ];
        return view('back.pages.auth.login', compact('data'));
    }

    public function forgotPassword(Request $request)
    {
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        return view('back.pages.auth.forgot', compact('data'));
    }

    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.email' => 'Invalid email address',
                'login_id.exists' => 'No account found for this email',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.exists' => 'No account found for this username'
            ]);
        }

        $cred = array(
            $fieldType => $request->login_id,
            'password' => $request->password
        );

        if (Auth::attempt($cred)) {
            if (Auth::user()->status == UserStatus::Inactive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently inactive. Please contact support at (support@hakimblog.com) for further assistance.');
            }

            if (Auth::user()->status == UserStatus::Inactive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently pending. Please, check your email for further instruction or contact support at (support@hakimblog.com) for further assistance.');
            }

            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->withInput()
                ->with('fail', 'Incorrect password.');
        }
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'The :attribute is required',
            'email.email' => 'Invalid email address',
            'email.exists' => 'We can not find a user with this email address'
        ]);

        $user = User::where('email', $request->email)->first();
        $token = base64_encode(Str::random(64));
        $oldToken = DB::table('password_reset_tokens')->where('email', $request->email)
            ->exists();
        if ($oldToken) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        }

        $actionLink = route('admin.reset_password', ['token' => $token]);
        if (Mail::to($user->email)->sendNow(new ForgotPasswordMail('Forgot Password', $actionLink))) {
            return redirect()->route('admin.forgot_password')->with('success', 'We have emailed your password reset link.');
        } else {
            return redirect()->route('admin.forgot_password')->with('fail', 'Something went wrong. Resetting password link is not sent. Try again later.');
        }
    }
    public function resetPassword(string $token = null)
    {
        $token = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$token) {
            return redirect()->route('admin.forgot_password')->with('fail', 'Invalid token. Please request another reset password link');
        }

        $minuteDifference = Carbon::createFromFormat('Y-m-d H:i:s', $token->create_at)->diffInMinutes(Carbon::now());
        if ($minuteDifference > 15) {
            return redirect()->route('admin.forgot_password')->with('fail', 'The password reset link has been expired. Please request a new link.');
        }

        $data = [
            'pageTitle' => 'Reset Password Page',
            'token' => $token->token
        ];

        return view('back.pages.auth.reset-password', $data);
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:5|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$token) {
            return redirect()->route('admin.forgot_password')->with('fail', 'Invalid token. Please request another reset password link');
        }
        $user = User::where('email', $token->email)->first();
        User::where('email', $user->email)
            ->update([
                'password' => Hash::make($request->new_password)
            ]);
        $user->notify(new PasswordResetNotification());
        DB::table('password_reset_tokens')
            ->where([
                'token' => $token->token,
                'email' => $token->email
            ])->delete();
        return redirect()->route('admin.login')->with('success', 'Password has been reset successfully.');
    }
}
