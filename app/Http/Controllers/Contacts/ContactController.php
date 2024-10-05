<?php

namespace App\Http\Controllers\Contacts;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
use App\Imports\ContactsImport;
use App\Models\Contacts;
use Illuminate\Http\Request;
use JWTAuth;
use GetResponses;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    // Get all contacts for the authenticated user
    public function index()
    {
        //
    }

    // Create a new contact
    public function store(Request $request)
    {
        //
    }

    // Get specific contact details
    public function show($id)
    {
        //
    }

    // Update contact
    public function update(Request $request, $id)
    {
        //
    }

    // Delete a contact
    public function destroy($id)
    {
        //
    }
    // Export contacts to CSV
    public function exportCsv()
    {
        //
    }

    // Import contacts from CSV
    public function importCsv(Request $request)
    {
        //
    }
}
