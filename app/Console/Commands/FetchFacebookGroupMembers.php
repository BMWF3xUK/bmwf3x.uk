<?php

namespace App\Console\Commands;

use App\Events\UpdateGroupMembers;
use App\Listeners\GroupMembersUpdateRequested;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchFacebookGroupMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "bmwf3x:get-group-members";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "this command runs automatically every 30mins to get a full list of all the members";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $user = User::where("token_expires_at", ">=", Carbon::now(config("app.timezone")))
                                ->orderBy("token_expires_at", "desc")
                                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->error("No valid tokens in the database to fetch users from...");

            return false;
        }

        $this->info("using the access token from \"{$user->name}\" to fetch users");
        $this->info($user->token);

        $event = new UpdateGroupMembers($user->token);
        with(new GroupMembersUpdateRequested())->handle($event);
        // event(new UpdateGroupMembers($user->access_token));
    }
}
