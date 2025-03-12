<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileTypeIcon extends Component
{
    public string $mime;

    public function __construct(string $mime)
    {
        $this->mime = $mime;
    }

    public function render()
    {
        return view('components.file-type-icon');
    }
}
