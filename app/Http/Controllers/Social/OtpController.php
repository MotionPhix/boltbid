<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpController extends Controller
{
  public function requestOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'remember' => 'required'
    ], [
      'email.exists' => 'We could not find matching credentials for your email'
    ]);

    $otp = Str::random(6);
    $expiresAt = Carbon::now()->addMinutes(10);

    Otp::create([
      'email' => $request->email,
      'otp' => $otp, 'remember' => $request->remember,
      'expires_at' => $expiresAt,
    ]);

    Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));

    return redirect(route('otp.seek'));
  }

  public function seekOtp()
  {
    return inertia('Auth/VerifyOtp');
  }

  public function verifyOtp(Request $request)
  {
    $request->validate(['email' => 'required|email|exists:users,email', 'otp' => 'required|string',]);
    $otpRecord = Otp::where('email', $request->email)->where('otp', $request->otp)->where('expires_at', '>', Carbon::now())->first();

    if (!$otpRecord) {
      return back()->withErrors(
        ['otp' => 'Invalid or expired OTP.'], 'otp'
      );
    }

    $user = User::where('email', $request->email)->first();

    Auth::login($user, $otpRecord->remember);

    return redirect()->route('dashboard');
  }
}
