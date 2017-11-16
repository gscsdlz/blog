<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 13:23
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {

    }

    public function login()
    {
        $set = new SettingModel();
        return view('admin.login', [
           'adminEmail' => $set->adminEmail,
        ]);
    }

    public function checkEmail(Request $request)
    {
        $set = new SettingModel();
        $lastTime = Session::get('lastCheckTime', null);

        if ( !is_null($lastTime) && (time() - $lastTime < 60) ) {   //超时定义为60秒
            Session::put('lastCheckTime', time());
            return response()->json(['status' => false, 'info' => 'TError']); //Time Error
        }
        Session::put('lastCheckTime', time());
        $email = $request->get('email');
        if($email != $set->adminEmail) {
            return response()->json(['status' => false, 'info' => 'EError']); //Email Error
        } else {
            $vcode = substr(md5(rand(10000, 99999)), rand(0, 20), rand(6, 10));

            Session::put('vcode', $vcode);
            Session::put('vcodeTime', 3);

            Mail::send('mail.vcode', ['vcode' => $vcode], function($message) use ($email) {
                $to = $email;
                $message->to($to)->subject("登录验证码");
            });
            if(empty(Mail::failures()))
                return response()->json(['status' => true]);
            else
                return response()->json(['status' => false, 'info' => 'SError']); //System Error
        }

    }

    public function doLogin(Request $request)
    {
        if (Session::get('vcodeTime', 0) <= 0) {
            Session::forget('vcode');
            return response()->json(['status' => false, 'info' => 'TError']);
        }
        $vcode = $request->get('vcode');

        if($vcode == Session::get('vcode')) {
            Session::put('username', config('blog.username'));

            return response()->json(['status' => true]);
        } else {
            Session::put('vcodeTime', Session::get('vcodeTime') - 1);
            return response()->json(['status' => false]);
        }
    }
}