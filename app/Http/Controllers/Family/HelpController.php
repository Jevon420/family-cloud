<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    public function index()
    {
        return view('family.help.index');
    }

    public function show($section)
    {
        $sections = [
            'files' => [
                'title' => 'Files Management',
                'description' => 'Learn how to upload, download, and manage your files in Family Cloud.',
                'steps' => [
                    [
                        'title' => 'Uploading Files',
                        'content' => 'To upload a file, go to the Files section in your dashboard, click on "Upload File" button. Select a file from your computer and click "Upload". You can also specify a folder to upload your file to.',
                        'icon' => 'upload'
                    ],
                    [
                        'title' => 'Downloading Files',
                        'content' => 'To download a file, navigate to the file you want to download and click the "Download" button. The file will be saved to your computer\'s default download location.',
                        'icon' => 'download'
                    ],
                    [
                        'title' => 'Managing Files',
                        'content' => 'You can view your files in the Files section. Click on a file to view its details. From there, you can download the file or view its information.',
                        'icon' => 'manage'
                    ]
                ]
            ],
            'folders' => [
                'title' => 'Folders Management',
                'description' => 'Learn how to create, navigate, and manage folders to organize your files.',
                'steps' => [
                    [
                        'title' => 'Creating Folders',
                        'content' => 'To create a new folder, go to the Folders section and click "New Folder". Enter a name for your folder and click "Create".',
                        'icon' => 'create'
                    ],
                    [
                        'title' => 'Navigating Folders',
                        'content' => 'Click on a folder to view its contents. You can navigate back to the parent folder by clicking the back arrow at the top of the page.',
                        'icon' => 'navigate'
                    ],
                    [
                        'title' => 'Organizing Files',
                        'content' => 'When uploading a file, you can select which folder to store it in. You can also create subfolders within folders to further organize your files.',
                        'icon' => 'organize'
                    ]
                ]
            ],
            'galleries' => [
                'title' => 'Galleries Management',
                'description' => 'Learn how to create and manage photo galleries to showcase your memories.',
                'steps' => [
                    [
                        'title' => 'Creating Galleries',
                        'content' => 'To create a new gallery, go to the Galleries section and click "Create Gallery". Enter a name and description for your gallery and click "Create".',
                        'icon' => 'create'
                    ],
                    [
                        'title' => 'Adding Photos',
                        'content' => 'Once you\'ve created a gallery, you can add photos to it. Click on the gallery, then click "Upload Photos". Select one or multiple photos from your computer and click "Upload".',
                        'icon' => 'add'
                    ],
                    [
                        'title' => 'Managing Galleries',
                        'content' => 'You can view and manage your galleries in the Galleries section. Click on a gallery to view its photos. You can add more photos, view photo details, or download photos.',
                        'icon' => 'manage'
                    ]
                ]
            ],
            'photos' => [
                'title' => 'Photos Management',
                'description' => 'Learn how to upload, view, and manage your photos in Family Cloud.',
                'steps' => [
                    [
                        'title' => 'Uploading Photos',
                        'content' => 'To upload photos, navigate to a gallery and click "Upload Photos". You can select multiple photos from your computer at once.',
                        'icon' => 'upload'
                    ],
                    [
                        'title' => 'Viewing Photos',
                        'content' => 'Click on a photo to view it in full size. You can navigate between photos using the left and right arrows.',
                        'icon' => 'view'
                    ],
                    [
                        'title' => 'Downloading Photos',
                        'content' => 'To download a photo, click on it to view it in full size, then click the "Download" button. The photo will be saved to your computer\'s default download location.',
                        'icon' => 'download'
                    ]
                ]
            ]
        ];

        if (!array_key_exists($section, $sections)) {
            abort(404);
        }

        return view('family.help.show', [
            'section' => $sections[$section],
        ]);
    }
}
