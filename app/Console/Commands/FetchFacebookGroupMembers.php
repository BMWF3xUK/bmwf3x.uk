<?php

namespace App\Console\Commands;

use App\Events\UpdateGroupMembers;
use App\Listeners\GroupMembersUpdateRequested;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);

        try {
            // look for Alan Wynn in the DB, and use my api token
            $user = User::findOrFail(10159282182215512);
        } catch (ModelNotFoundException $e) {
            $user = false;
        }

        // if my token has expired, or I am not there, then use the longest lasting token
        if (false !== $user && $user->token_expires_at < Carbon::now(config("app.timezone"))) {
            try {
                $user = User::where("token_expires_at", ">=", Carbon::now(config("app.timezone")))
                                    ->orderBy("token_expires_at", "desc")
                                    ->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $this->error("No valid tokens in the database to fetch users from...");

                return false;
            }
        }

        $this->info("using the access token from \"{$user->name}\" to fetch users");
        $this->info($user->token);

        $event = new UpdateGroupMembers($user->token);
        with(new GroupMembersUpdateRequested())->handle($event);
    }
}
