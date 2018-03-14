<?php

namespace App\Listeners;

use App\Events\UpdateGroupMembers;
use App\FacebookGroup;
use App\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Wilderborn\Partyline\Partyline;

class GroupMembersUpdateRequested implements ShouldQueue
{
    protected $cache_key = "-members";

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
        FacebookGroup::all()->each(function($group) use ($event) {
            $url = "https://graph.facebook.com/v2.10/{$group->id}/members?" . http_build_query([
                "access_token" => $event->access_token,
                "pretty" => false,
                "limit" => 25,
            ]);

            $response = $this->graphApiRequest($url);

            while (isset($response["data"]) && !empty($response["data"])) {
                collect($response["data"])->each(function($member) use ($group) {
                    try {
                        $member = Member::findOrFail($member["id"]);
                    } catch (ModelNotFoundException $e) {
                        $member = $this->storeMember($group, $member);
                    }

                    if (!$member->groups->has($group->id)) {
                        print "adding {$member["name"]} from the {$group->name} group to the members db - {$member["id"]}" . PHP_EOL;
                        $member->groups()->attach($group);
                    }

                    $this->updateCache($group, $member);
                });

                if (!isset($response["paging"]) || !isset($response["paging"]["next"])) {
                    $response = null;

                    break;
                }

                $response = $this->graphApiRequest($response["paging"]["next"]);
            }

            $this->clearRemovedMembers($group);
        });
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

    protected function storeMember(FacebookGroup $group, $member)
    {
        print "creating {$member["name"]} in the members db - {$member["id"]}" . PHP_EOL;

        return Member::create($member);
    }

    protected function updateCache(FacebookGroup $group, $member)
    {
        $cache_key = str_slug($group->name) . $this->cache_key;
        $members = Cache::get($cache_key, []);
        $members[] = $member["id"];

        Cache::forever($cache_key, $members);
    }

    protected function clearRemovedMembers(FacebookGroup $group)
    {
        $cache_key = str_slug($group->name) . $this->cache_key;
        $current_members = Cache::get($cache_key, []);

        $group->members->each(function($member) use ($group, $current_members) {
            if (in_array($member->id, $current_members)) {
                return true;
            }

            print "removing {$member["name"]} from the group {$group->name} - {$member["id"]}" . PHP_EOL;
            $member->groups()->detatch($group);

            if ($member->groups->count() > 0) {
                return true;
            }

            print "removing {$member["name"]} from the members db - {$member["id"]}" . PHP_EOL;
            $member->delete();
        });

        Cache::forget($cache_key);
    }
}
