<?php

namespace Clairence\Helper;

use Clairence\Entity\Student;

class PaymentDetailEncoder
{
    static public function encode(string $id_pembayaran_kuliah, string $gross_amount, Student $mhs): string | false
    {
        $email = $mhs->getEmail();
        $name = $mhs->getName();
        $phoneNumber = $mhs->getPhoneNumber();

        return json_encode([
            "payment_type" => "bank_transfer",
            "transaction_details" => [
                "order_id" => $id_pembayaran_kuliah,
                "gross_amount" => $gross_amount
            ],
            "customer_details" => [
                "email" => $email,
                "first_name" => $name,
                "last_name" => "",
                "phone" => $phoneNumber
            ],
            "bank_transfer" => [
                "bank" => "bca"
            ]
        ]);
    }
}
