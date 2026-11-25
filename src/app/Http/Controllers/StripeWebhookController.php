<?php

// このファイルが属する名前空間(Laravelのコントローラーの置き場所)
namespace App\Http\Controllers;

// リクエスト(Stripeから届いた通知)を受け取るためのクラス
use Illuminate\Http\Request;
// ログ(storage/logs/laravel.log)に記録するためのクラス
use Illuminate\Support\Facades\Log;
// Stripeの署名を検証するクラス(constructEvent を持っている)
use Stripe\Webhook;
// 署名が合わなかったときに投げられる例外
use Stripe\Exception\SignatureVerificationException;
// 通知の中身(JSON)が壊れているときに投げられる例外
use UnexpectedValueException;

// StripeからのWebhookを受け取るコントローラー
class StripeWebhookController extends Controller
{
    // POST /api/webhook/stripe が呼ばれたときに動くメソッド
    public function handle(Request $request)
    {
        // 通知の本文を「加工していない生の文字列」で取り出す(署名の計算は、生の本文で行うため)
        $payload = $request->getContent();

        // Stripeが付けた署名を、リクエストヘッダーから取り出す
        $sigHeader = $request->header('Stripe-Signature');

        // .env の STRIPE_WEBHOOK_SECRET(whsec_...)を、config経由で取り出す
        $secret = config('services.stripe.webhook_secret');

        // 署名の検証でエラーが出る可能性があるので、try で囲む
        try {
            // 本文・署名・シークレットを照合して、本物ならイベント(通知の中身)を返す
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (UnexpectedValueException $e) {
            // 通知の中身が、JSONとして壊れていた場合
            Log::warning('Stripe Webhook: 不正なペイロード');
            // 「リクエストが不正」を意味する 400 を返して終了する
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            // 署名が合わない = Stripeからではない、または書き換えられた通知
            Log::warning('Stripe Webhook: 署名の検証に失敗');
            // 400 を返して終了する(これ以降の処理には進ませない)
            return response('Invalid signature', 400);
        }

        // 署名の検証を通った「本物の通知」だけが、ここから先に進む
        // 通知の種類が「支払いが成功した」ときだけ処理する
        if ($event->type === 'payment_intent.succeeded') {
            // 通知の中にある、支払い(PaymentIntent)の情報を取り出す
            $paymentIntent = $event->data->object;

            // 支払いIDと金額をログに記録する(課題の「支払いIDをログに出力」にあたる部分)
            Log::info('Stripe決済成功', [
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount,
            ]);
        }

        // Stripeに「受け取れた」と伝える。2xx を返さないと、Stripeが再送してくる
        return response('OK', 200);
    }
}