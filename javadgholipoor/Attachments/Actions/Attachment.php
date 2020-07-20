<?php


namespace LaraBase\Attachments\Actions;


trait Attachment {

    public function echoIcon() {
        if ($this->type != 'image') {
            if (empty($this->poster)) {
                echo 'icon';
            } else {
                echo 'icon full-image';
            }
        }
    }

    public function poster($thumbnail = false) {

        if (!empty($this->poster)) {

            if ($thumbnail) {
                $uploaderThumbnailWidth  = config('uploader.thumbnailWidth');
                $uploaderThumbnailHeight = config('uploader.thumbnailHeight');
                return url(uploaderGenerateNameBySize($this->poster, $uploaderThumbnailWidth, $uploaderThumbnailHeight));
            }

            return url($this->poster);

        }

        $theme = uploaderTheme();
        $icon  = uploaderIcon($this->extension);
        return image($icon, 'uploader');

    }

    public function thumbnail() {

        if ($this->type == 'image') {

            $uploaderThumbnailWidth  = config('uploader.thumbnailWidth');
            $uploaderThumbnailHeight = config('uploader.thumbnailHeight');

            if ($this->in == '1') {
                $thumbnail = url(uploaderGenerateNameBySize($this->path, $uploaderThumbnailWidth, $uploaderThumbnailHeight));
            } else if ($this->in == '2') {
                $thumbnail = route('renderImage', ['id' => $this->id, 'width' => $uploaderThumbnailWidth, 'height' => $uploaderThumbnailHeight]);
            } else if ($this->in == '3') {

            }

        } else {
            if (empty($this->poster)) {
                $icon = uploaderIcon($this->extension);
                $thumbnail = image($icon, 'uploader');
            } else {
                $thumbnail = $this->poster(true);
            }
        }

        return $thumbnail;
    }

    public function play() {
        if ($this->in == '1') {
            return url($this->path);
        } else if ($this->in == '2') {
            return route('download', ['id' => $this->id, 'title' => $this-> title]);
        } else if ($this->in == '3') {
        }
    }

    public function scopeExtensions($query, $extensions = []) {

        if (!is_array($extensions)) {
            $extensions = explode(' ', $extensions);
        }

        if (!empty($extensions)) {
            if (!in_array('all', $extensions)) {
                $query->whereIn('extension', $extensions);
            }
        }

    }

}
