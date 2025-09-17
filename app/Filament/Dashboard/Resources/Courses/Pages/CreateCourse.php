<?php

namespace App\Filament\Dashboard\Resources\Courses\Pages;

use App\Filament\Dashboard\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
