<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProcessUploadedFile implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Log::info('Processing file: ' . $this->path);
        $filePath = storage_path('app/public/' . $this->path);

        if (! file_exists($filePath)) {
            Log::error('File does not exist: ' . $filePath);
            return;
        }

        try {
            $image = Image::read($filePath);
            Log::info('Image loaded successfully.');

            // $encodedImage = $image->encode('jpeg', 30);
            $encodedImage = $image->resize(300, 200);

            Log::info('Image encoded successfully.');

            $compressedPath = str_replace('originals', 'compressed', $this->path);
            Storage::disk('public')->put($compressedPath, $encodedImage);
            Log::info('File processed and saved: ' . $compressedPath);
        } catch (\Exception $e) {
            Log::error('Error processing file: ' . $e->getMessage());
        }
    }
}
