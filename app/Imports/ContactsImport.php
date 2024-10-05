<?php

namespace App\Imports;

use App\Models\Contacts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToCollection, WithHeadingRow
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['name']) || empty($row['phone'])) {
                // Skip rows with missing name or phone
                continue;
            }

            // Check for duplicates (e.g., based on phone number)
            if ($this->user->contacts()->where('phone', $row['phone'])->exists()) {
                continue; // Skip duplicate contacts
            }
            // Add new contact
            $attr = [
                "user_id" => $this->user->id,
                "name" => $row['name'],
                "phone" => $row['phone'],
            ];

            $fill = [
                ...$attr,
                "email" => $row['email'] ?? null
            ];
            $contact = Contacts::firstOrNew($attr)->fill($fill);
            $contact->save();

        }
    }
}
