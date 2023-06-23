<?php

namespace Clairence\Entity;

class PaymentResponse
{
    public function __construct(
        private string $status_code,
        private string $status_message,
        private string $transaction_id,
        private string $order_id,
        private string $merchant_id,
        private string $gross_amount,
        private string $currency,
        private string $payment_type,
        private string $transaction_time,
        private string $transaction_status,
        private string $fraud_status,
        private string $bank,
        private string $va_number,
        private string $expiry_time,
    ) {
    }

    static public function createPaymentResponse(array $reponse): PaymentResponse
    {
        return new PaymentResponse(
            status_code: $reponse['status_code'],
            status_message: $reponse['status_message'],
            transaction_id: $reponse['transaction_id'],
            order_id: $reponse['order_id'],
            merchant_id: $reponse['merchant_id'],
            gross_amount: $reponse['gross_amount'],
            currency: $reponse['currency'],
            payment_type: $reponse['payment_type'],
            transaction_time: $reponse['transaction_time'],
            transaction_status: $reponse['transaction_status'],
            fraud_status: $reponse['fraud_status'],
            bank: $reponse['va_numbers'][0]->bank,
            va_number: $reponse['va_numbers'][0]->va_number,
            expiry_time: $reponse['expiry_time'],
        );
    }

    /**
     * Get the value of status_code
     */
    public function getStatusCode(): string
    {
        return $this->status_code;
    }

    /**
     * Set the value of status_code
     */
    public function setStatusCode(string $status_code): void
    {
        $this->status_code = $status_code;
    }

    /**
     * Get the value of status_message
     */
    public function getStatusMessage(): string
    {
        return $this->status_message;
    }

    /**
     * Set the value of status_message
     */
    public function setStatusMessage(string $status_message): void
    {
        $this->status_message = $status_message;
    }

    /**
     * Get the value of transaction_id
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    /**
     * Set the value of transaction_id
     */
    public function setTransactionId(string $transaction_id): void
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * Get the value of order_id
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * Set the value of order_id
     */
    public function setOrderId(string $order_id): void
    {
        $this->order_id = $order_id;
    }

    /**
     * Get the value of merchant_id
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * Set the value of merchant_id
     */
    public function setMerchantId(string $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * Get the value of gross_amount
     */
    public function getGrossAmount(): string
    {
        return $this->gross_amount;
    }

    /**
     * Set the value of gross_amount
     */
    public function setGrossAmount(string $gross_amount): void
    {
        $this->gross_amount = $gross_amount;
    }

    /**
     * Get the value of currency
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * Get the value of payment_type
     */
    public function getPaymentType(): string
    {
        return $this->payment_type;
    }

    /**
     * Set the value of payment_type
     */
    public function setPaymentType(string $payment_type): void
    {
        $this->payment_type = $payment_type;
    }

    /**
     * Get the value of transaction_time
     */
    public function getTransactionTime(): string
    {
        return $this->transaction_time;
    }

    /**
     * Set the value of transaction_time
     */
    public function setTransactionTime(string $transaction_time): void
    {
        $this->transaction_time = $transaction_time;
    }

    /**
     * Get the value of transaction_status
     */
    public function getTransactionStatus(): string
    {
        return $this->transaction_status;
    }

    /**
     * Set the value of transaction_status
     */
    public function setTransactionStatus(string $transaction_status): void
    {
        $this->transaction_status = $transaction_status;
    }

    /**
     * Get the value of fraud_status
     */
    public function getFraudStatus(): string
    {
        return $this->fraud_status;
    }

    /**
     * Set the value of fraud_status
     */
    public function setFraudStatus(string $fraud_status): void
    {
        $this->fraud_status = $fraud_status;
    }

    /**
     * Get the value of bank
     */
    public function getBank(): string
    {
        return $this->bank;
    }

    /**
     * Set the value of bank
     */
    public function setBank(string $bank): void
    {
        $this->bank = $bank;
    }

    /**
     * Get the value of va_number
     */
    public function getVaNumber(): string
    {
        return $this->va_number;
    }

    /**
     * Set the value of va_number
     */
    public function setVaNumber(string $va_number): void
    {
        $this->va_number = $va_number;
    }

    /**
     * Get the value of expiry_time
     */
    public function getExpiryTime(): string
    {
        return $this->expiry_time;
    }

    /**
     * Set the value of expiry_time
     */
    public function setExpiryTime(string $expiry_time): void
    {
        $this->expiry_time = $expiry_time;
    }
}
