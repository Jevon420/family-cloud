<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\File;
use App\Models\Folder;
use App\Models\MediaVisibility;

class FixMediaVisibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:fix-visibility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing media visibility records for all media';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fixing missing media visibility records...');

        try {
            // Fix Galleries first
            $this->info('Processing Galleries...');
            $this->fixGalleries();

            // Then Photos
            $this->info('Processing Photos...');
            $this->fixPhotos();

            // Then Files
            $this->info('Processing Files...');
            $this->fixFiles();

            // Finally Folders
            $this->info('Processing Folders...');
            $this->fixFolders();

            $this->info('All media visibility records have been created.');

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());
            return 1;
        }
    }

    protected function fixGalleries()
    {
        $galleriesCount = 0;
        $galleries = Gallery::whereDoesntHave('visibility')->get();

        foreach ($galleries as $gallery) {
            MediaVisibility::create([
                'media_type' => Gallery::class,
                'media_id' => $gallery->id,
                'visibility' => 'private',
                'created_by' => $gallery->created_by ?? 1,
            ]);
            $galleriesCount++;
        }

        $this->info("Fixed {$galleriesCount} galleries.");
    }    protected function fixPhotos()
    {
        $photosCount = 0;
        $photos = Photo::whereDoesntHave('visibility')->get();

        foreach ($photos as $photo) {
            $visibility = 'private';

            // Don't rely on relationships, use direct queries
            if ($photo->gallery_id) {
                // First ensure the gallery has a visibility record
                $galleryVisibility = MediaVisibility::where('media_type', Gallery::class)
                    ->where('media_id', $photo->gallery_id)
                    ->first();

                if (!$galleryVisibility) {
                    $gallery = Gallery::find($photo->gallery_id);
                    if ($gallery) {
                        MediaVisibility::create([
                            'media_type' => Gallery::class,
                            'media_id' => $gallery->id,
                            'visibility' => 'private',
                            'created_by' => $gallery->created_by ?? 1,
                        ]);
                    }
                }

                // Now get the gallery's visibility again
                $galleryVisibility = MediaVisibility::where('media_type', Gallery::class)
                    ->where('media_id', $photo->gallery_id)
                    ->first();

                if ($galleryVisibility) {
                    $visibility = $galleryVisibility->visibility;
                }
            }

            MediaVisibility::create([
                'media_type' => Photo::class,
                'media_id' => $photo->id,
                'visibility' => $visibility,
                'created_by' => $photo->created_by ?? 1,
            ]);
            $photosCount++;
        }

        $this->info("Fixed {$photosCount} photos.");
    }    protected function fixFiles()
    {
        $filesCount = 0;
        $files = File::whereDoesntHave('visibility')->get();

        foreach ($files as $file) {
            $visibility = 'private';

            // Don't rely on relationships, use direct queries
            if ($file->folder_id) {
                // First ensure the folder has a visibility record
                $folderVisibility = MediaVisibility::where('media_type', Folder::class)
                    ->where('media_id', $file->folder_id)
                    ->first();

                if (!$folderVisibility) {
                    $folder = Folder::find($file->folder_id);
                    if ($folder) {
                        MediaVisibility::create([
                            'media_type' => Folder::class,
                            'media_id' => $folder->id,
                            'visibility' => 'private',
                            'created_by' => $folder->created_by ?? 1,
                        ]);
                    }
                }

                // Now get the folder's visibility again
                $folderVisibility = MediaVisibility::where('media_type', Folder::class)
                    ->where('media_id', $file->folder_id)
                    ->first();

                if ($folderVisibility) {
                    $visibility = $folderVisibility->visibility;
                }
            }

            MediaVisibility::create([
                'media_type' => File::class,
                'media_id' => $file->id,
                'visibility' => $visibility,
                'created_by' => $file->created_by ?? 1,
            ]);
            $filesCount++;
        }

        $this->info("Fixed {$filesCount} files.");
    }    protected function fixFolders()
    {
        $foldersCount = 0;
        $folders = Folder::whereDoesntHave('visibility')->get();

        foreach ($folders as $folder) {
            $visibility = 'private';

            // Don't rely on relationships, use direct queries
            if ($folder->parent_id) {
                // First ensure the parent folder has a visibility record
                $parentVisibility = MediaVisibility::where('media_type', Folder::class)
                    ->where('media_id', $folder->parent_id)
                    ->first();

                if (!$parentVisibility) {
                    $parent = Folder::find($folder->parent_id);
                    if ($parent) {
                        MediaVisibility::create([
                            'media_type' => Folder::class,
                            'media_id' => $parent->id,
                            'visibility' => 'private',
                            'created_by' => $parent->created_by ?? 1,
                        ]);
                    }
                }

                // Now get the parent's visibility again
                $parentVisibility = MediaVisibility::where('media_type', Folder::class)
                    ->where('media_id', $folder->parent_id)
                    ->first();

                if ($parentVisibility) {
                    $visibility = $parentVisibility->visibility;
                }
            }

            MediaVisibility::create([
                'media_type' => Folder::class,
                'media_id' => $folder->id,
                'visibility' => $visibility,
                'created_by' => $folder->created_by ?? 1,
            ]);
            $foldersCount++;
        }

        $this->info("Fixed {$foldersCount} folders.");
    }
}
