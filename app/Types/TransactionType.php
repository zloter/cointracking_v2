<?php

namespace App\Types;

enum TransactionType: int
{
 case REWARD_BONUS = 57;
 case DEPOSIT = 1;
 case DIVIDENDS_INCOME = 54;
 case TRADE = 0;
 case WITHDRAWAL = 2;
 case OTHER_EXPENSE = 40;
 case INCOME_NO_TAX = 91;
 case EXPENSE_NO_TAX = 41;
}
