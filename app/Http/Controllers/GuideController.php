<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class GuideController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            "auth",
            "member",
        ]);
    }

    public function index()
    {
        return $this->getDirectoryContents();
    }

    public function view($dir)
    {
        return $this->getDirectoryContents("/{$dir}");
    }

    protected function getDirectoryContents($dir = null) {
        $directory = storage_path("app/guides{$dir}");

        $directories = File::directories($directory);
        $directories = collect($directories)->transform(function($directory) {
            return basename($directory);
        })->values();

        $files = File::files($directory);
        $files = collect($files)->transform(function($file) {
            return [
                "name" => basename($file),
                "modified_at" => with(new Carbon)->timestamp(filemtime($file))->toCookieString(),
                // "modified_at" => with(new Carbon)->timestamp(filemtime($file)),
                "size" => $this->human_filesize($file),
            ];
        })->values();

        return compact("directories", "files");
    }

    protected function human_filesize($file, $decimals = 2) {
        $bytes = filesize($file);
        $sz = " KMGTP";
        $factor = floor((strlen($bytes) - 1) / 3);
        $descriptor = @$sz[$factor];
        $descriptor = trim($descriptor) . "B";

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $descriptor;
    }
}
