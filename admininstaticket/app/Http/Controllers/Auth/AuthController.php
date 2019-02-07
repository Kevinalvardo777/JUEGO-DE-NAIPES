<?php
namespace Instaticket\Http\Controllers\Auth;
use Instaticket\User;
use Validator;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Instaticket\Entidades\TipoUsuarios;
use Instaticket\Entidades\Usuario;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Instaticket\Entidades\ParametroGeneral;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller { 
use RedirectsUsers,
    ThrottlesLogins;
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    use RedirectsUsers;
    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin() {
        return $this->showLoginForm();
    }
    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
        $view = property_exists($this, 'loginView') ? $this->loginView : 'auth.authenticate';
        if (view()->exists($view)) {
            return view($view);
        }
       return view('auth.login');
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
        return $this->login($request);
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $this->validateLogin($request);
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $usuario = Usuario::where('usuario_username', $request->usuario_username)->first();
        if (!empty($usuario)) {
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUsuarioTelevisor);
            if (!empty($objPGeneral) && !empty($objPGeneral->parametro_general_valor)) {
                if ($usuario->tipo_usuario_id != $objPGeneral->parametro_general_valor) {
                    $credentials = [
                            'usuario_username'       => $request->get('usuario_username'),
                            'usuario_password' =>  $request->get('usuario_password'),
                            'tipo_usuario_id'         => $usuario->tipo_usuario_id,
                     ];
                    if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
                        Log::info('Ingreso USUARIO: ' . $request->usuario_username);
                         
                        return $this->handleUserWasAuthenticated($request, $throttles);
                    }
                    if ($throttles && !$lockedOut) {
                        $this->incrementLoginAttempts($request);
                    }
                } else {
                    Log::error('Usuario es Televisor Instaticket: '.$request->usuario_username . ' No es permitido ingresar');
                    return redirect()->back() ->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
                                        $this->loginUsername() => 'Usuario es Televisor Instaticket.',
                    ]);
                }
            } else {
                Log::error('No se ha encontrado el parámetro: TIPO_USUARIO_TELEVISOR');
                return redirect()->back() ->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
                                    $this->loginUsername() => 'No se ha encontrado el parámetro: TIPO_USUARIO_TELEVISOR',]);
            }
        } else {
            return redirect()->back() ->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
                                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
        }
        return redirect()->back()
                 ->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
                            $this->loginUsername() => $this->getFailedLoginMessage(),
        ]);
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request) {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'usuario_password' => 'required',
        ]);
    }
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles) {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }
        return redirect()->intended($this->redirectPath());
    }
    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request) {
        return redirect()->back()
                        ->withInput($request->only($this->loginUsername(), 'remember'))
                        ->withErrors([
                            $this->loginUsername() => $this->getFailedLoginMessage(),
        ]);
    }
    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage() {
        return Lang::has('auth.failed') ? Lang::get('auth.failed') : 'These credentials do not match our records.';
    }
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request) {
        return $request->only($this->loginUsername(), 'usuario_password');
    }
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout() {
        return $this->logout();
    }
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        Auth::guard($this->getGuard())->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    /**
     * Get the guest middleware for the application.
     */
    public function guestMiddleware() {
        $guard = $this->getGuard();
        return $guard ? 'guest:' . $guard : 'guest';
    }
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername() {
        return property_exists($this, 'usuario_username') ? $this->usuario_username : 'usuario_username';
    }
    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait() {
        return in_array(
                ThrottlesLogins::class, class_uses_recursive(static::class)
        );
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return string|null
     */
    protected function getGuard() {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'usuario_nombre' => 'required|max:255',
                    'usuario_username' => 'required|usuario_username|max:255|unique:usuario',
                    'usuario_password' => 'required|min:6|confirmed',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        return User::create([
                    'usuario_nombre' => $data['usuario_nombre'],
                    'usuario_username' => $data['usuario_username'],
                    'usuario_password' => bcrypt($data['usuario_password']),
        ]);
    }
}