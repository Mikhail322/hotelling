<?php

namespace App\Enums;

enum BookingStatuses: string
{
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
    case IN_PROGRESS = 'in progress';
}
