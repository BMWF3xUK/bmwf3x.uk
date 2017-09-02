<?php

namespace App\Http\Controllers\Auth;

use App\Events\UpdateGroupMembers;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    protected function setConfigForRedirect()
    {
        // this is hacky, but its so that we can still do `php artisan route:list`
        $redirect = config("services.facebook.redirect");
        $redirect = route($redirect);

        Config::set("services.facebook.redirect", $redirect);
    }

    public function onboard()
    {
        $this->setConfigForRedirect();

        return Socialite::driver("facebook")->scopes([
            // "user_managed_groups",
        ])->redirect();
    }

    public function onboardResponse(Request $request)
    {
        $this->setConfigForRedirect();
        $facebook = Socialite::driver("facebook");

        try {
            $access_token = $facebook->getAccessTokenResponse($request->code);
            $fb_user = $facebook->user();
        } catch (Exception $e) {
            return redirect(route("onboard.facebook"));
        } catch (ClientException $e) {
            return redirect(route("onboard.facebook"));
        }

        $long_life_access_token = $this->extendTokenLife($access_token["access_token"]);

        // event(new UpdateGroupMembers($long_life_access_token["access_token"]));

        $user = User::selectOrCreateFromFacebookUser($fb_user);
        Auth::login($user, true);

        return redirect(route("home"));
    }

    public function extendTokenLife($accessToken)
    {
        // Get cURL resource
        $ch = curl_init();

        $url = "https://graph.facebook.com/oauth/access_token?" . http_build_query([
            "grant_type" => "fb_exchange_token",
            "client_id" => config("services.facebook.client_id"),
            "client_secret" => config("services.facebook.client_secret"),
            "fb_exchange_token" => $accessToken,
        ]);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Send the request & save response to $resp
        $resp = curl_exec($ch);

        // Close request to clear up some resources
        curl_close($ch);

        return json_decode($resp, true);
    }
}
