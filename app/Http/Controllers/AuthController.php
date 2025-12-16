<?php

namespace App\Http\Controllers;

use App\Events\AppNotificationEvent;
use App\Http\Requests\AdditionalInformationRequest;
use App\Http\Requests\FirstStepSignInRequest;
use App\Models\Region;
use App\Models\RegistrationPending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->with(['error' => 'Invalid credentials']);
        }

        $user = auth()->user();
        $user->update(['last_login' => now()]);

        if (!$user->is_approved) {
            Auth::logout();
            return back()->with(['error' => 'Your account is not approved yet']);
        }

        return redirect()->route( $user->role === 'admin' || $user->role === 'super_admin' ? 'admin.dashboard' : 'user.dashboard' );
    }

    public function showSignUp()
    {
        return view('auth.sign_up');
    }

    public function firstStepSignUp(FirstStepSignInRequest $request)
    {
        $user = User::create([
            'type' => $request->type,
            'email' => $request->email,
            'company_name' => $request->firm_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password),
        ]);

        session(['signup_user_id' => $user->id]);

        return redirect()
            ->route('showAdditionalInformation')
            ->with('success', 'Account created. Please provide additional information.');
    }

    public function showAdditionalInformation()
    {
        return view('auth.sign_up_additional_info', [
            'regions' => Region::all(),
            'userId' => session('signup_user_id'),
        ]);
    }

    public function secondStepSignUp(AdditionalInformationRequest $request)
    {
        DB::transaction(function () use ($request) {

            $user = User::findOrFail(session('signup_user_id'));

            $user->update([
                'region_id' => $request->region_id,
                'phone' => $request->phone_number,
                'language' => $request->language,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
            ]);

            RegistrationPending::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            event(new AppNotificationEvent(
                user: $user,
                userMessage: null,
                adminMessage: 'New user registered and awaits approval.'
            ));
        });

        return redirect('/')->with('success', 'Account created. Please wait for admin approval.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
