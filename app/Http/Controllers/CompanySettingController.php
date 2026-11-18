<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = CompanySetting::first() ?? new CompanySetting(['interest' => 3]);

        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $company = CompanySetting::first() ?? new CompanySetting(['interest' => 3]);
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'detail' => 'required',
            'address' => 'required',
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        $data = [
            'name' => $request->name ?? '',
            'detail' => $request->detail ?? '',
            'interest' => $request->interest ?? '',
            'phone' => $request->phone ?? '',
            'address' => $request->address ?? '',
            'default_loan_note' => $request->default_loan_note ?? '',
            'default_invoice_note' => $request->default_invoice_note ?? '',
        ];

        if ($image = $request->file('logo')) {
            $profileImage = $image->hashName();
            $image->move(public_path('images/company'), $profileImage);
            $data['logo'] = $profileImage;
        }

        $company->fill($data)->save();

        return redirect()->route('company.index', withLang());
    }
}
