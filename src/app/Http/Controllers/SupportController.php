<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe;

class SupportController extends Controller
{
    // 応援の金額(円)。今週は定額にする
    private const AMOUNT = 500;

    // 「応援する」ボタンが押されたとき:Stripe の決済ページを作って、そこに移動させる
    public function create(Post $post)
    {
        // Stripe に「この秘密キーの持ち主だ」と名乗る(キーは config() 経由で .env から読む)
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Stripe に、決済ページ(Checkout Session)を作ってもらう
            $session = Session::create([
                // 支払い方法を、カードだけに限定する(旧: payment_method_types は、使えなくなった)
                'allowed_payment_method_types' => ['card'],
                // 何を、いくらで売るか
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'jpy',                                               // 通貨は日本円
                        'product_data' => ['name' => '「' . $post->title . '」の書き手を応援'], // 決済ページに出る商品名
                        'unit_amount'  => self::AMOUNT,                                        // 金額(円)
                    ],
                    'quantity' => 1,                                                           // 数量は1
                ]],
                // 1回きりの支払い
                'mode'        => 'payment',
                // 決済のあとに「誰が、どの記事を応援したか」が分かるように、Stripe に預けておく
                'metadata'    => ['post_id' => $post->id, 'user_id' => auth()->id()],
                // 決済が成功したときに戻ってくるページ(Stripe が、決済の ID を入れてくれる)
                'success_url' => route('supports.success') . '?session_id={CHECKOUT_SESSION_ID}',
                // 決済をやめたときに戻るページ(元の記事)
                'cancel_url'  => route('posts.show', $post),
            ]);
        } catch (CardException $e) {
            // カードエラー(残高不足など):ログに記録して、ユーザーにメッセージを出す
            Log::error('Stripe CardException: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'カードエラーが発生しました: ' . $e->getError()->message]);
        } catch (InvalidRequestException $e) {
            // 送った内容が不正:コード側の問題なので、ログに記録する
            Log::error('Stripe InvalidRequest: ' . $e->getMessage());
            return back()->withErrors(['payment' => '決済リクエストが不正です']);
        } catch (ApiConnectionException $e) {
            // Stripe につながらない(ネットワーク障害)
            Log::error('Stripe ApiConnection: ' . $e->getMessage());
            return back()->withErrors(['payment' => '通信エラーが発生しました。時間をおいて再試行してください']);
        }

        // 作った決済ページに、ユーザーを移動させる
        return redirect($session->url);
    }

    // 決済が終わって戻ってきたとき:決済を確認して、応援の履歴を保存する
    public function success(Request $request)
    {
        // Stripe に、秘密キーで名乗る
        Stripe::setApiKey(config('services.stripe.secret'));

        // URL の session_id を使って、Stripe に決済の結果を問い合わせる(URL をそのまま信じない)
        $session = Session::retrieve($request->query('session_id'));

        // 支払いが済んでいなければ、保存せずに一覧に戻す
        if ($session->payment_status !== 'paid') {
            return redirect()->route('posts.index')->with('error', '決済が完了していません');
        }

        // 応援の履歴を保存する(同じ session_id が、すでにあれば、新しく作らない)
        Support::firstOrCreate(
            ['stripe_session_id' => $session->id],   // 探す条件
            [
                'user_id' => $session->metadata->user_id,   // 応援した人(決済のときに預けた値)
                'post_id' => $session->metadata->post_id,   // 応援した記事
                'amount'  => $session->amount_total,        // 金額(円の場合、そのまま円)
            ]
        );

        // お礼のページを表示する
        return view('supports.success');
    }
}