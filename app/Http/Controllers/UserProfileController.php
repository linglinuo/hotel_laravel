<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'string',
            'phone' => 'string',
            'birthday' => 'string',
            'photo' => 'string'
        ]);
    
        UserProfile::create($request->all());
     
        return redirect()->with('success','Profile created successfully.');
    }

    public function update(Request $request, UserProfile $profile)
    {
        $request->validate([
            'nickname' => 'string',
            'phone' => 'string',
            'birthday' => 'string',
            'photo' => 'string'
        ]);
    
        $profile->update($request->all());
    
        return redirect()->with('success','Profile updated successfully');
    }
}
