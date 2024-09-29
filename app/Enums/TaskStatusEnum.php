<?php

namespace App\Enums;

enum TaskStatusEnum :string
{

case PENDING = 'pending';
case PROCESSING = 'processing';
case COMPLETED = 'completed';
case CANCELED = 'canceled';

}
