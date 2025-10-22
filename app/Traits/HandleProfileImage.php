<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleProfileImage
{
    /**
     * Upload and save profile image
     *
     * @param UploadedFile $image
     * @return string
     */
    public function uploadProfileImage(UploadedFile $image)
    {
        // Delete old image if exists
        if ($this->profile_image) {
            Storage::disk('public')->delete($this->profile_image);
        }

        // Store new image
        $path = $image->store('profile-images', 'public');
        $this->update(['profile_image' => $path]);

        return $path;
    }

    /**
     * Delete profile image
     *
     * @return bool
     */
    public function deleteProfileImage()
    {
        if ($this->profile_image) {
            Storage::disk('public')->delete($this->profile_image);
            $this->update(['profile_image' => null]);
            return true;
        }
        return false;
    }
}
