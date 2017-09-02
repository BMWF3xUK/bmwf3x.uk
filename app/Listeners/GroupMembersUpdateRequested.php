<?php

namespace App\Listeners;

use App\Events\UpdateGroupMembers;
use App\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Wilderborn\Partyline\Partyline;

class GroupMembersUpdateRequested implements ShouldQueue
{
    protected $cache_key = "bmwf3x-members";

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdateGroupMembers  $event
     * @return void
     */
    public function handle(UpdateGroupMembers $event)
    {
        $url = "https://graph.facebook.com/v2.10/" . config("services.facebook.group_id") . "/members?" . http_build_query([
            "access_token" => $event->access_token,
            "pretty" => false,
            "limit" => 25,
        ]);

        $response = $this->graphApiRequest($url);

        while (isset($response["data"]) && !empty($response["data"])) {
            collect($response["data"])->each(function($member) {
                try {
                    Member::findOrFail($member["id"]);
                } catch (ModelNotFoundException $e) {
                    $this->storeMember($member);
                }

                $this->updateCache($member);
            });

            if (!isset($response["paging"]) || !isset($response["paging"]["next"])) {
                $response = null;

                break;
            }

            $response = $this->graphApiRequest($response["paging"]["next"]);
        }

        $this->clearRemovedMembers();
    }

    protected function graphApiRequest($url)
    {
        // Get cURL resource
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Send the request & save response to $resp
        $resp = curl_exec($ch);

        // Close request to clear up some resources
        curl_close($ch);

        return json_decode($resp, true);
    }

    protected function storeMember($member)
    {
        print "adding {$member["name"]} to the members db - {$member["id"]}" . PHP_EOL;

        Member::create($member);
    }

    protected function updateCache($member)
    {
        $members = Cache::get($this->cache_key, []);
        $members[] = $member["id"];

        Cache::forever($this->cache_key, $members);
    }

    protected function clearRemovedMembers()
    {
        $current_members = Cache::get($this->cache_key, []);

        Member::all()->each(function($member) use ($current_members) {
            if (in_array($member->id, $current_members)) {
                return true;
            }

            print "removing {$member["name"]} from the members db - {$member["id"]}" . PHP_EOL;
            $member->delete();
        });

        Cache::forget($this->cache_key);
    }
}
