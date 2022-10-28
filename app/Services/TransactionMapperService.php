<?php

namespace App\Services;

use App\Types\Column;
use App\Types\Transaction;
use App\Types\TransactionType;

class TransactionMapperService
{

    public function csvToTransactions(array $csv, bool $withHeaders = true): array
    {
        if ($withHeaders) {
            array_shift($csv);
        }
        $fees = array_filter($csv, function ($record) {
            return array_key_exists(Column::TYPE->value, $record) && $record[Column::TYPE->value] === "Fee";
        });
        $transactions = [];
        foreach ($csv as $record) {
            switch ($record[Column::TYPE->value] ?? null) {
                case "Bonus":
                case "Deposit":
                case "Dividend":
                case "Withdrawal":
                    $transactions[] = $this->createTransactions(
                        $record,
                        $this->matchType($record[Column::TYPE->value])
                    );
                    break;
                case "Exchange":
                    $id = $record[Column::TRANSACTION_ID->value];
                    $fee = current(array_filter($fees, function($f) use ($id) {
                        return $id === $f[Column::TRANSACTION_ID->value];
                    }));
                    $transactions[] = $this->createTransactions(
                        $record,
                        $this->matchType($record[Column::TYPE->value]),
                        false,
                        $fee ?: null
                    );
                    break;
                case "Liquidation":
                    $transactions[] = $this->createTransactions(
                        $record,
                        TransactionType::OTHER_EXPENSE
                    );

                    $transactions[] = $this->createTransactions(
                        $record,
                        TransactionType::INCOME_NO_TAX,
                        true
                    );
                    break;
                case "WithdrawalCredit":
                    $transactions[] = $this->createTransactions(
                        $record,
                        TransactionType::EXPENSE_NO_TAX,
                        true
                    );
                    $transactions[] = $this->createTransactions(
                        $record,
                        TransactionType::INCOME_NO_TAX
                    );
                    $transactions[] = $this->createTransactions(
                        $record,
                        TransactionType::WITHDRAWAL
                    );
                    break;
                default:
            }
        }
        return $transactions;
    }

    private function matchType(string $type): TransactionType
    {
        return match($type) {
            "Bonus" => TransactionType::REWARD_BONUS,
            "Deposit" =>  TransactionType::DEPOSIT,
            "Dividend" =>  TransactionType::DIVIDENDS_INCOME,
            "Exchange" =>  TransactionType::TRADE,
            "Withdrawal" =>  TransactionType::WITHDRAWAL
        };
    }

    private function createTransactions(array $record, TransactionType $type, bool $loan = false, array $fee = null): Transaction
    {

        $t = new Transaction(
            strtotime($record[Column::DATETIME->value]),
            $type->value,
            null,
            null,
            null,
            null,
            null,
            null,
            $record[Column::DETAILS->value],
            null,
            null,
        );
        if ($fee && '' != $fee[Column::OUTPUT_AMOUNT->value]) {
            $t->fee = abs($fee[Column::OUTPUT_AMOUNT->value]);
            $t->feeCoin =$fee[Column::OUTPUT_CURRENCY->value];
        }
        if (in_array($type, [
            TransactionType::WITHDRAWAL,
            TransactionType::TRADE,
            TransactionType::OTHER_EXPENSE,
            TransactionType::EXPENSE_NO_TAX,
        ])) {
            $t->sell = abs($record[Column::OUTPUT_AMOUNT->value]);
            $t->sellCoin =  $record[Column::OUTPUT_CURRENCY->value];
            $t->sellOrigin = $loan ? "Nexo Loan" : "Nexo";
        }

        if (in_array($type, [
            TransactionType::REWARD_BONUS,
            TransactionType::DEPOSIT,
            TransactionType::DIVIDENDS_INCOME,
            TransactionType::TRADE,
            TransactionType::INCOME_NO_TAX,
        ])) {
            $t->buy = abs($record[Column::INPUT_AMOUNT->value]);
            $t->buyCoin =  $record[Column::INPUT_CURRENCY->value];
            $t->buyOrigin = $loan ? "Nexo Loan" : "Nexo";
        }

        return $t;
    }

    public function transactionsToJson(mixed $transactions)
    {
        $arrayTransactions = [];
        foreach ($transactions as $transaction) {
            $t = [];
                $t['time'] = $transaction->time;
                $t['transaction_type'] = $transaction->type;
            if ($transaction->buy) {
                $t['buy']  =$transaction->buy;
            }
            if ($transaction->buyCoin) {
                $t['buy_coin'] = $transaction->buyCoin;
            }
            if ($transaction->sell) {
                $t['sell'] = $transaction->sell;
            }
            if ($transaction->sellCoin) {
                $t['sell_coin'] = $transaction->sellCoin;
            }
            if ($transaction->fee) {
                $t['fee'] = $transaction->fee;
            }
            if ($transaction->feeCoin) {
                $t['fee_coin'] = $transaction->feeCoin;
            }
            if ($transaction->comment) {
                $t['comment'] = $transaction->comment;
            }
            if ($transaction->buyOrigin) {
                $t['buy_origin'] = $transaction->buyOrigin;
            }
            if ($transaction->sellOrigin) {
                $t['sell_origin'] = $transaction->sellOrigin;
            }
            $arrayTransactions[] = $t;
        }
        return json_encode($arrayTransactions);
    }
}

