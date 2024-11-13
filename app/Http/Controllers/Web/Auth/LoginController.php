<?php

namespace Vanguard\Http\Controllers\Web\Auth;

use Auth;
use Authy;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vanguard\Events\User\LoggedIn;
use Vanguard\Events\User\LoggedOut;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\Auth\LoginRequest;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Services\Auth\ThrottlesLogins;
use Vanguard\Services\Auth\TwoFactor\Contracts\Authenticatable;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct(private UserRepository $users)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('auth.login', [
            'socialProviders' => config('auth.social.providers')
        ]);
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse|Response
     * @throws BindingResolutionException
     */
    public function login(LoginRequest $request)
    {
        // Throttling check
        $throttles = setting('throttle_enabled');
        $to = $request->has('to') ? "?to=" . $request->get('to') : '';

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->getCredentials();

        if (! Auth::validate($credentials)) {
            if ($throttles) {
                $this->incrementLoginAttempts($request);
            }
            return redirect()->to('login' . $to)->withErrors(trans('auth.failed'));
        }

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if ($user->isBanned()) {
            return redirect()->to('login' . $to)->withErrors(__('Your account is banned by administrator.'));
        }

        Auth::login($user, setting('remember_me') && $request->get('remember'));

        return $this->authenticated($request, $throttles, $user);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  Request $request
     * @param  bool $throttles
     * @param $user
     * @return RedirectResponse|Response
     */
    protected function authenticated(Request $request, $throttles, $user)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (setting('2fa.enabled') && Authy::isEnabled($user)) {
            return $this->logoutAndRedirectToTokenPage($request, $user);
        }

        event(new LoggedIn);

        // Always redirect to /dashboard after successful login
        return redirect()->to('/dashboard');
    }

    /**
     * @param Request $request
     * @param Authenticatable $user
     * @return RedirectResponse
     */
    protected function logoutAndRedirectToTokenPage(Request $request, Authenticatable $user)
    {
        Auth::logout();

        $request->session()->put('auth.2fa.id', $user->id);

        return redirect()->route('auth.token');
    }

    /**
     * Log the user out of the application.
     *
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        event(new LoggedOut);

        Auth::logout();

        return redirect('login');
    }
}
