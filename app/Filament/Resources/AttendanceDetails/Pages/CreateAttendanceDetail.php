<?php

namespace App\Filament\Resources\AttendanceDetails\Pages;

use App\Filament\Resources\AttendanceDetails\AttendanceDetailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceDetail extends CreateRecord
{
    protected static string $resource = AttendanceDetailResource::class;
}
