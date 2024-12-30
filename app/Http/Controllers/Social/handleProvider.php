<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class handleProvider extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    try {
      $googleUser = Socialite::driver('google')->stateless()->user();
      $authUser = $this->findOrCreateUser($googleUser);
      Auth::login($authUser, true);

      return redirect()->route('home'); // Ensure 'home' is a named route in your web.php
    } catch (\Exception $e) {
      return redirect()->route('login')->withErrors(['email' => 'Authentication failed. Please try again.']);
    }
  }

  public function findOrCreateUser($googleUser)
  {
    $authUser = User::where('provider_id', $googleUser->id)->first();

    if ($authUser) {
      return $authUser;
    }

    return User::create([
      'name' => $googleUser->name,
      'email' => $googleUser->email,
      'provider' => 'google',
      'provider_id' => $googleUser->id,
      'password' => null,
    ]);
  }
}
