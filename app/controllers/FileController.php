<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

/**
 * File Controller
 * 
 * Handles file upload operations
 */
class FileController extends Controller
{
    /**
     * File upload form
     */
    public function uploadForm()
    {
        $this->view('file.upload', [
            'title' => 'Upload File',
            'maxSize' => MAX_UPLOAD_SIZE,
            'allowedExtensions' => ALLOWED_EXTENSIONS
        ]);
    }
    
    /**
     * Process file upload
     */
    public function upload()
    {
        // Check if file was uploaded
        if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
            Session::flash('error', 'No file was uploaded');
            $this->redirectToRoute('file.upload');
            return;
        }
        
        // Validate file
        $fileValidation = Security::validateFileUpload($_FILES['file']);
        
        if (!$fileValidation['status']) {
            Session::flash('error', $fileValidation['message']);
            $this->redirectToRoute('file.upload');
            return;
        }
        
        // Create the upload directory if it doesn't exist
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }
        
        // Create user-specific directory
        $userId = Session::get('user_id');
        $userDir = UPLOAD_DIR . $userId . '/';
        
        if (!is_dir($userDir)) {
            mkdir($userDir, 0755, true);
        }
        
        // Set the target file path
        $targetFile = $userDir . $fileValidation['filename'];
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            Session::flash('success', 'File uploaded successfully');
        } else {
            Session::flash('error', 'Failed to upload file');
        }
        
        $this->redirectToRoute('file.upload');
    }
}