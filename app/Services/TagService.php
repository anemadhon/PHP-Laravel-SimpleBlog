<?php

namespace App\Services;

class TagService
{
    public function format(string $name)
    {
        return str_replace(' ', '-', strtolower($name));
    }

    public function delete(object $tag)
    {
        $tag->articles()->detach();

        $tag->delete();
    }
}
