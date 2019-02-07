<?php
namespace Instaticket\Http\Controllers;
use Illuminate\Support\Facades\Session;
use PHPMailer;
use Auth;
use Illuminate\Http\Request;
use Instaticket\Entidades\ParametroGeneral;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        Log::info('Ingreso Interfaz HOME, con usuario: ' . Auth::user()->username);
        return view('home');
    }
    /**
     * METODO QUE PERMITE EL ENVIO DE EMAIL  SELECCIONADO
     * DEL NUTRICIONISTA SELECCIONADO 
     * @param Request $request
     * @return type
     */
    public static function enviarMensaje($host, $usuario, $password, $smtp, $port, $para, $titulo, $mensaje) {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $host;             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $usuario;
            $mail->Password = $password;
            $mail->SMTPSecure = $smtp;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $port;                                    // TCP port to connect to
            $mail->setFrom($usuario);
            $mail->addCC('carelyzbastidasflores@gmail.com');
            $mail->addAddress($para);
            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $titulo;
            $mail->Body = $mensaje;
            if (!$mail->send()) {
                Session::flash('messageError', $mail->ErrorInfo);
                return redirect()->back();
            } else {
                Session::flash('message', 'Mensaje Enviado con éxito al email: ' . $para);
            }
        } catch (Exception $ex) {
            Log::error('CONTROLLER Home/enviarMensaje'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public static function encrypt($pure_string, $encryption_key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return $encrypted_string;
    }
    public static function decrypt($encrypted_string, $encryption_key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
        return $decrypted_string;
    }
}
