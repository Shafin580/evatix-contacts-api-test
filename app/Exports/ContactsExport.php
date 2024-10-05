<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    // Method to return the data collection
    public function collection()
    {
        return $this->user->contacts()->select('name', 'phone', 'email')->get();
    }

    // Method to define the Excel headers
    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Email'
        ];
    }
}
