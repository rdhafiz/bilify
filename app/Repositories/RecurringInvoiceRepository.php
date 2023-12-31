<?php

namespace App\Repositories;

use App\Models\Invoices;
use App\Models\RecurringInvoices;
use App\Models\User;

class RecurringInvoiceRepository
{
    public static function RecurringUid(User $user, string $invoiceUserType, string $invoiceUserId): int
    {
        $uid = RecurringInvoices::where('user_id', $user['id'])->where($invoiceUserType, $invoiceUserId)->count();
        return $uid + 1;
    }
    public static function save(array $invoiceData): array|RecurringInvoices
    {
        $invoiceModel = new RecurringInvoices();
        $invoiceModel->uid = $invoiceData['uid'];
        $invoiceModel->user_id = $invoiceData['user_id'];
        $invoiceModel->client_id = $invoiceData['client_id'] ?? null;
        $invoiceModel->payee_id = $invoiceData['payee_id'] ?? null;
        $invoiceModel->category_id = $invoiceData['category_id'];
        $invoiceModel->due_days = $invoiceData['due_days'];
        $invoiceModel->currency = $invoiceData['currency'];
        $invoiceModel->tax = $invoiceData['tax'] ?? 0;
        $invoiceModel->discount = $invoiceData['discount'] ?? 0;
        $invoiceModel->bonus = $invoiceData['bonus'] ?? 0;
        $invoiceModel->invoice_item_headings = $invoiceData['invoice_item_headings'] ?? null;
        $invoiceModel->start_date = $invoiceData['start_date'];
        $invoiceModel->frequency = $invoiceData['frequency'];
        $invoiceModel->next_schedule_date = null;
        $invoiceModel->end_date = $invoiceData['end_date'] ?? null;
        $invoiceModel->status = $invoiceData['status'];
        $invoiceModel->note = $invoiceData['note'] ?? null;
        if (!$invoiceModel->save()) {
            return ['message' => 'Cannot save invoice'];
        }
        return $invoiceModel;
    }
    public static function update(RecurringInvoices $invoiceModel, array $invoiceData): array|RecurringInvoices
    {
        $invoiceModel->category_id = $invoiceData['category_id'];
        $invoiceModel->due_days = $invoiceData['due_days'];
        $invoiceModel->currency = $invoiceData['currency'];
        $invoiceModel->tax = $invoiceData['tax'] ?? 0;
        $invoiceModel->discount = $invoiceData['discount'] ?? 0;
        $invoiceModel->bonus = $invoiceData['bonus'] ?? 0;
        $invoiceModel->invoice_item_headings = $invoiceData['invoice_item_headings'] ?? null;
        $invoiceModel->start_date = $invoiceData['start_date'];
        $invoiceModel->frequency = $invoiceData['frequency'];
        $invoiceModel->next_schedule_date = null;
        $invoiceModel->end_date = $invoiceData['end_date'] ?? null;
        $invoiceModel->status = $invoiceData['status'];
        $invoiceModel->note = $invoiceData['note'] ?? null;
        if (!$invoiceModel->save()) {
            return ['message' => 'Cannot update recurring invoice'];
        }
        return $invoiceModel;
    }
    public static function single(int $invoiceId): array|RecurringInvoices
    {
        $invoice = RecurringInvoices::where('id', $invoiceId)->with(['invoice_items', 'category', 'client', 'payee'])->first();
        if (!$invoice instanceof RecurringInvoices) {
            return ['message' => 'Cannot find recurring invoice'];
        }
        return $invoice;
    }
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = RecurringInvoices::where('user_id', $user['id'])->with(['invoice_items', 'category', 'client', 'payee']);
        if (!empty($filter['is_active'])) {
            $result->where('is_active', $filter['is_active']);
        } else {
            $result->where('is_active', 1);
        }
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $result->whereBetween('start_date', [$filter['start_date'].' 00:00:00', $filter['end_date'].' 23:59:59']);
        } else {
            if (!empty($filter['start_date'])) {
                $result->where('start_date', '>=', $filter['start_date'].' 00:00:00');
            }
            if (!empty($filter['end_date'])) {
                $result->where('end_date', '<=', $filter['end_date'].' 23:59:59');
            }
        }
        if (!empty($filter['client_id']) && !empty($filter['payee_id'])) {
            $result->where(function($q) use ($filter) {
                $q->where('client_id', '=', $filter['client_id']);
                $q->orWhere('payee_id', '=', $filter['payee_id']);
            });
        } else {
            if (!empty($filter['client_id'])) {
                $result->where('client_id', $filter['client_id']);
            }
            if (!empty($filter['payee_id'])) {
                $result->where('payee_id', $filter['payee_id']);
            }
        }
        if (!empty($filter['category_id'])) {
            $result->where('category_id', $filter['category_id']);
        }
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('uid', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        $result = $result->orderBy($pagination['sort_by'], $pagination['order_by'])->paginate($pagination['limit']);
        return $result;
    }
}
