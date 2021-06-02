<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use App\Models\User;
use Hash;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * ------------------------------
         * Login
         * ------------------------------
        */
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        /**
         * ------------------------------
         * Email Verification
         * ------------------------------
        */
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        /**
         * ------------------------------
         * Register
         * ------------------------------
        */
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        /**
         * ------------------------------
         * Reset Password
         * ------------------------------
        */
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        // RateLimiter::for('two-factor', function (Request $request) {
        //     return Limit::perMinute(5)->by($request->session()->get('login.id'));
        // });
    }
}
