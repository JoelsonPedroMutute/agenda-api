<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => 'sometimes|exists:appointments,id',
            'remind_at' => 'sometimes|date|after_or_equal:now',
            'method' => 'sometimes|in:email,message,notification',
        ];
    }

  
}


