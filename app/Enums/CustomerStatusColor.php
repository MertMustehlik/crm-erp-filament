<?php

namespace App\Enums;

enum CustomerStatusColor: string
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case DANGER = 'danger';
}
