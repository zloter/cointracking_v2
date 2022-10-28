<?php

namespace App\Types;

enum Column: int
{
    case TRANSACTION_ID = 0;
    case TYPE = 1;
    case INPUT_CURRENCY = 2;
    case INPUT_AMOUNT = 3;
    case OUTPUT_CURRENCY = 4;
    case OUTPUT_AMOUNT = 5;
    case IN_USD = 6;
    case DETAILS = 7;
    case OUTSTANDING_LOAN = 8;
    case DATETIME = 9;
}

