<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file']
        ];
    }


    public function uploadFile(UploadedFile $file, $current_image_id)
    {
        $this->image = $file;

        if ($this->validate()) {
            $this->deleteCurrentImage($current_image_id);
            return $this->saveImage();
        }

        return null;
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($current_image_id)
    {
        if ($current_image_id !== null) {
            $image = Image::findOne($current_image_id);

            if ($image !== null && $this->fileExists($image->url)) {
                unlink($image->url);
            }
        }
    }

    public function fileExists($currentImage)
    {
        if (!empty($currentImage)) {
            return file_exists($currentImage);
        }

        return false;
    }

    public function saveImage()
    {
        $filename = $this->generateFilename();

        $path = $this->getFolder() . $filename;
        $this->image->saveAs($path);

        $image = new Image;
        $image->url = $path;
        $image->save();

        return $image->id;
    }
}