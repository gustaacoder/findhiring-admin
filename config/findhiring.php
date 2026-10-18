<?php

return [

    'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-3.8-flash'),
        'timeout' => (int) env('GEMINI_TIMEOUT', 90),
        'max_attempts' => (int) env('GEMINI_MAX_ATTEMPTS', 3),
        'temperature' => (float) env('GEMINI_TEMPERATURE', 0.1),
        'max_output_tokens' => (int) env('GEMINI_MAX_OUTPUT_TOKENS', 8192),
        'prompt_version' => env('GEMINI_PROMPT_VERSION', 'v1'),
    ],

    'screening' => [
        'resume_disk' => env('RESUME_DISK', 'local'),
        'resume_directory' => env('RESUME_DIRECTORY', 'resumes'),
        'max_upload_size_kb' => (int) env('RESUME_MAX_SIZE_KB', 5120),
        'allowed_extensions' => ['pdf', 'docx'],
        'allowed_mime_types' => [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'max_resume_characters' => (int) env('RESUME_MAX_CHARACTERS', 20000),
    ],

];
