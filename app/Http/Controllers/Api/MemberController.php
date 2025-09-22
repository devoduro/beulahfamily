<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Search for members by name or email
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([]);
            }
            
            // Sanitize the search query
            $query = trim($query);
            $query = preg_replace('/[^a-zA-Z0-9\s@._-]/', '', $query);
            
            $members = Member::where('is_active', true)
                            ->where(function($q) use ($query) {
                                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                                  ->orWhere('email', 'LIKE', "%{$query}%")
                                  ->orWhere('member_id', 'LIKE', "%{$query}%");
                            })
                            ->select('id', 'first_name', 'last_name', 'email', 'member_id', 'photo_path as photo', 'phone', 'chapter')
                            ->limit(15)
                            ->get()
                            ->map(function($member) {
                                return [
                                    'id' => $member->id,
                                    'first_name' => $member->first_name ?? '',
                                    'last_name' => $member->last_name ?? '',
                                    'full_name' => trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')),
                                    'email' => $member->email ?? '',
                                    'member_id' => $member->member_id ?? '',
                                    'phone' => $member->phone ?? '',
                                    'chapter' => $member->chapter ?? '',
                                    'photo' => $member->photo ? asset('storage/' . $member->photo) : null,
                                    'initials' => strtoupper(
                                        substr($member->first_name ?? 'U', 0, 1) . 
                                        substr($member->last_name ?? 'U', 0, 1)
                                    )
                                ];
                            });
            
            \Log::info("Member search successful for query: '{$query}', found " . $members->count() . " members");
            
            return response()->json($members);
            
        } catch (\Exception $e) {
            \Log::error('Member search API error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to search members',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
