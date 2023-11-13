<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    public $timestamps = true;
    public function category()
    {
        return $this->hasMany(Category::class, 'id', 'category_id');
    }

    public function str_to_url($url)
    {
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }

    // Define a function to remove images from content and replace them
    public function replaceImagesInContent($content, $folder = "uploads/")
    {
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
    
        // Use a more precise regex to match the entire img tag, including the src attribute.
        preg_match_all('/<img[^>]+>/', $content, $matches);
    
        if (is_array($matches[0]) && count($matches[0]) > 0) {
            // Initialize the Image class once outside the loop.
            $image_class = new Image();
    
            foreach ($matches[0] as $match) {
                // Use a single regex to extract the src attribute value.
                if (preg_match('/src="data:image\/([^;]+);base64,([^"]+)"/i', $match, $matches2)) {
                    // Extract the image data and extension.
                    $base64Data = $matches2[2];
                    $extension = $matches2[1];
    
                    // Generate a unique filename based on a timestamp and a random string.
                    $filename = $folder . "base_64" . $image_class->generate_filename(50) . ".jpg";
    
                    // Replace the src attribute with the new filename.
                    $content = str_replace($matches2[0], 'src="' . $filename . '"', $content);
    
                    // Save the base64 data as an image file.
                    file_put_contents($filename, base64_decode($base64Data));
                }
            }
        }
    
        return $content;
    }

    function editPostAndRemoveUnusedImages($newContent, $oldContent) {
        // Function to search for <img> tags and extract filenames
        function extractFilenamesFromImgTags($content) {
            $pattern = '/<img[^>]+src=("|\')(.*?)("|\')/i';
            preg_match_all($pattern, $content, $matches);
            return $matches[2];
        }
    
        // Extract filenames from the new and old content
        $newFilenames = extractFilenamesFromImgTags($newContent);
        $oldFilenames = extractFilenamesFromImgTags($oldContent);
    
        // Find filenames that are in the old content but not in the new content
        $unusedFilenames = array_diff($oldFilenames, $newFilenames);
    
    
        foreach ($unusedFilenames as $filename) {
            $imagePath = $filename;
    
            // Check if the image file exists and then delete it
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        // Now, you can process the new content as needed (e.g., updating the post in your application)
        // ...
        return $this->replaceImagesInContent($newContent);
    }
    
    public function deletePostAndFiles($post)
    {
        // Delete the image associated with the post
        $imageName = $post->image;  // Assuming the post has an 'image' field that contains the main image file name.
        $uploadsDirectory = public_path('uploads');  // Path to the directory where the image files are stored.
    
        if (is_dir($uploadsDirectory)) {
            $files = glob($uploadsDirectory . '/*');  // Get a list of all files in the directory.
    
            // Extract filenames from the post content and store them in an array.
            $content = $post->content;  // Assuming the post has a 'content' field.
            $pattern = '/\b\w+\.\w+\b/';  // Assuming filenames are alphanumeric with an extension.
            preg_match_all($pattern, $content, $matches);
            $filenames = $matches[0];
    
            // Loop through the list of files and delete files with similar names.
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
    
                if (file_exists($file) && in_array($filename . '.' . $extension, $filenames)) {
                    unlink($file);  // Delete files with similar names.
                }
            }
    
            // Delete the main image associated with the post.
            $mainImagePath = $uploadsDirectory . '/' . $imageName;
           
            if (file_exists($mainImagePath)) {
                unlink($mainImagePath);
            }
        }
        return;
    }
    
    
}
