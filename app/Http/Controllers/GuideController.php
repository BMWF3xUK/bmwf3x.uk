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

        if (!file_exists(storage_path("app/guides"))) {
            mkdir(storage_path("app/guides"), 775, true);
        }
    }

    public function index()
    {
        return $this->getDirectoryContents();
    }

    public function view($catchall)
    {
        return $this->getDirectoryContents("/{$catchall}");
    }

    public function download($catchall)
    {
        $directory = explode("/", $catchall);
        $filename = array_pop($directory);
        $directory = implode("/", $directory);
        $directory = storage_path("app/guides/{$directory}");

        if (!file_exists($directory)) {
            return app()->abort(404, "Folder does not exist...");
        }

        if (!file_exists("{$directory}/{$filename}")) {
            return app()->abort(404, "File does not exist...");
        }

        return response()->download("{$directory}/{$filename}", $filename);
    }

    protected function getDirectoryContents($dir = null) {
        $directory = storage_path("app/guides{$dir}");
        $pwd = trim($dir, "/");

        $directories = File::directories($directory);
        $directories = collect($directories)->transform(function($directory) {
            return basename($directory);
        })->values();

        $files = File::files($directory);
        $files = collect($files)->transform(function($file) {
            return [
                "name" => basename($file),
                // "modified_at" => with(new Carbon)->timestamp(filemtime($file))->toCookieString(),
                "modified_at" => with(new Carbon)->timestamp(filemtime($file)),
                "size" => $this->human_filesize($file),
            ];
        })->values();

        $previous_dir = explode("/", $pwd);
        $dir_name = array_pop($previous_dir);
        $previous_dir = implode("/", $previous_dir);
        $previous_dir = trim($previous_dir, "/");

        if ($dir_name == "/" || empty($dir_name)) {
            $dir_name = null;
        }

        return view("guides", compact(
            "pwd",
            "previous_dir",
            "dir_name",
            "directories",
            "files"
        ));
    }

    protected function human_filesize($file, $decimals = 2) {
        $bytes = filesize($file);
        $sz = " kmgtp";
        $factor = floor((strlen($bytes) - 1) / 3);
        $descriptor = @$sz[$factor];
        $descriptor = trim($descriptor) . "b";

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $descriptor;
    }
}
