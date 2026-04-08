<?php

namespace App\Support;

enum IntentType: string
{
    case BOOKING = 'booking';
    case DELIVERY_ORDER = 'delivery_order';
    case SALES_ORDER = 'sales_order';
    case NPWP = 'npwp';
    case CUSTOMS = 'customs';
    case GENERAL = 'general';
}
