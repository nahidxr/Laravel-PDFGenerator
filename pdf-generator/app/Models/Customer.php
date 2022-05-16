<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Mail;

class Customer extends Model
{
    use HasFactory;
    public function sendCustomerEmail($customer, $pdf)
    {
        $path = 'public/storage/uploads/' . '-' . rand() . '_' . time() . '.' . 'pdf';
        Storage::put($path, $pdf->output());

        $viewData['fname']              = $customer->fname;
        $viewData['lname']              = $customer->lname;
        $viewData['email']              = $customer->email;
        Mail::send('email_templates.email_customer_detail', $viewData, function ($m) use ($customer, $pdf, $path) {
            $m->from('nahidulislam.devskill@gmail.com', env('APP_NAME'));
            $m->to('nahidulislam.devskill@gmail.com', $customer->email)->subject('Customer Details');
            $m->to($customer->email, $customer["email"])
                ->subject($customer->fname)
                ->attachData($pdf->output(), $path,  [
                    'mime' => 'application/pdf',
                    'as' => $customer->fname,
                ]);
        });
    }
}
