<?php

return [
    'show_warnings' => false,

    // Some production deployments do not expose the app's public directory at
    // {base_path}/public. DomPDF hard-fails when that path cannot be resolved,
    // so fall back to the project root and allow an explicit env override.
    'public_path' => env(
        'DOMPDF_PUBLIC_PATH',
        is_dir(base_path('public')) ? base_path('public') : base_path()
    ),
];