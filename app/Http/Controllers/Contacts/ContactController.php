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
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve 'per_page' from the request or use a default value of 10 if input not given
        $perPage = $request->input('per_page', 10);

        return GetResponses::returnData($user->contacts()->paginate($perPage), 200);
    }

    // Create a new contact
    public function store(Request $request)
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // validate inputs
        $validatedData = validator($request->only(
            'name',
            'email',
            'phone',
        ), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Return Error response if input validation fails
        if ($validatedData->fails()) {
            $jsonError = response()->json($validatedData->errors()->all(), 400);
            return GetResponses::validationError($jsonError->original, 400);
        }
        $attr = [
            "user_id" => $user->id,
            "name" => $request->name,
            "phone" => $request->phone,
        ];

        $fill = [
            ...$attr,
            "email" => $request->email ?? null
        ];

        // Create Contact
        $contact = Contacts::firstOrNew($attr)->fill($fill);
        $contact->save();

        // Return Error Response if contact creation fails
        if (!$contact) {
            return GetResponses::validationError("Contacts not registered!", 400);
        }

        // return success response if contact creation succeed
        return GetResponses::returnData(["message" => "Contacts successfully registered!"], 201);
    }

    // Get specific contact details by id
    public function show($id)
    {
        // authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // eloquent query to find specific contact by id
        $contact = $user->contacts()->find($id);

        // return responses if contacts found or not found
        if (!$contact) {
            return GetResponses::validationError('Contact not found', 400);
        }
        return GetResponses::returnData($contact, 200);
    }

    // Update contact
    public function update(Request $request, $id)
    {
        // authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // eloquent query to find specific contact by id
        $contact = $user->contacts()->find($id);

        // return error responses if contact not found
        if (!$contact) {
            return GetResponses::validationError('Contact not found', 400);
        }

        // update contact query
        $contact->update($request->all());

        // return success message if contact updated successfully
        return GetResponses::returnData(["message" => "Contact updated successfully!"], 200);
    }

    // Delete a contact
    public function destroy($id)
    {
        // authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // eloquent query to find specific contact by id
        $contact = $user->contacts()->find($id);

        // return error responses if contact not found
        if (!$contact) {
            return GetResponses::validationError('Contact not found', 400);
        }

        // eloquent query to delete contact
        $contact->delete();

        // return success message if contact deleted
        return GetResponses::returnData(["message" => "Contact deleted!"], 200);
    }
    // Export contacts to CSV
    public function exportCsv()
    {
        // authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // export contacts in CSV - will be downloaded through browser if browser makes a request to this api
        return Excel::download(new ContactsExport($user), 'contacts.xlsx');
    }

    // Import contacts from CSV
    public function importCsv(Request $request)
    {
        // authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Validate that file is uploaded and is a CSV
        $validatedData = validator($request->only(
            'file',
        ), [
            'file' => 'required|mimes:csv,xlsx',
        ]);

        // return error responses if input validation fails
        if ($validatedData->fails()) {
            $jsonError = response()->json($validatedData->errors()->all(), 400);
            return GetResponses::validationError($jsonError->original, 400);
        }

        // Import contacts with validation
        Excel::import(new ContactsImport($user), $request->file('file'));

        // return success message after database is populated
        return GetResponses::returnData(["message" => "Contacts imported successfully!"], 201);
    }
}
