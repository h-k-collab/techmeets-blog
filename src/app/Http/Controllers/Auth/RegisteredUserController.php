<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * 新規登録の画面を表示する
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 新規登録のフォームが送信されたときの処理
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 入力のチェック(名前・メール・パスワード)。ダメなら、ここで止まって、フォームに戻る
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // メールは、同じものがすでに登録されていないこと(unique)
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // パスワードは、確認用の入力(confirmed)と一致し、決まった強さがあること
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザーを DB に保存する(パスワードは、ハッシュ化してから)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 「ユーザーが登録された」というイベントを発生させる(Breeze の標準の処理)
        event(new Registered($user));

        // 登録した人を、そのままログインさせる
        Auth::login($user);

        // ウェルカムメールを送る。失敗しても、登録は完了させたいので、try / catch で囲む
        try {
            // 登録したメールアドレスに、ユーザーの名前を入れた WelcomeMail を送る
            Mail::to($user->email)->send(new WelcomeMail($user->name));
        } catch (\Throwable $e) {
            // メールの送信に失敗したら、ログに記録するだけで、画面は止めない
            Log::error('ウェルカムメールの送信に失敗: ' . $e->getMessage());
        }

        // ダッシュボードに移動する
        return redirect(route('dashboard', absolute: false));
    }
}