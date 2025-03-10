<?php

if (!function_exists('uploadImg')) {
    /**
     * Upload image and return info
     * 
     * @param string $inputName Name of file input
     * @return array ['code' => int, 'name' => string|null, 'error' => string|null]
     */
    function uploadImg($inputName) 
    {
        if (!isset($_FILES[$inputName])) {
            return [
                'code' => 0,
                'name' => null,
                'error' => 'No file uploaded'
            ];
        }

        $file = $_FILES[$inputName];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'code' => 0,
                'name' => null,
                'error' => 'Upload failed with error code: ' . $file['error']
            ];
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '_' . time() . '.' . $extension;

        return [
            'code' => 1,
            'name' => $filename,
            'error' => null
        ];
    }
}