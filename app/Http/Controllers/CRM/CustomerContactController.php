<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerContact;

class CustomerContactController extends Controller
{
    public function store(Request $request, $customerId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // If this is being set as default, unset any existing default
        if ($request->is_default) {
            CustomerContact::where('customer_id', $customerId)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $contact = CustomerContact::create([
            'customer_id' => $customerId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact added successfully',
            'contact' => $contact
        ]);
    }

    public function update(Request $request, $customerId, $contactId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $contact = CustomerContact::where('id', $contactId)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        // If this is being set as default, unset any existing default
        if ($request->is_default && !$contact->is_default) {
            CustomerContact::where('customer_id', $customerId)
                ->where('id', '!=', $contactId)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_default' => $request->is_default ?? $contact->is_default,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'contact' => $contact
        ]);
    }


    public function destroy($customerId, $contactId)
    {
        $contact = CustomerContact::where('id', $contactId)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        // If this is the default contact, we need to handle it
        if ($contact->is_default) {
            // Find another contact to make default if available
            $newDefault = CustomerContact::where('customer_id', $customerId)
                ->where('id', '!=', $contactId)
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }


        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact moved to trash successfully'
        ]);
    }

    public function index($customerId)
    {
        $query = CustomerContact::where('customer_id', $customerId);
        
        // Only include non-trashed items by default or when trashed=0
        if (request('trashed', '0') === '0') {
            $query->whereNull('deleted_at');
        }
        
        $contacts = $query->orderBy('name')->get();

        return response()->json($contacts);
    }

    public function getContacts($customerId)
    {
        $contacts = CustomerContact::where('customer_id', $customerId)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'is_default' => $contact->is_default,
                    'created_at' => $contact->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $contact->updated_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json($contacts);
    }
}
