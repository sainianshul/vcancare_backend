<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NurseService
{
    /**
     * Update Nurse profile details from Admin panel
     *
     * @param User $user The Nurse to update
     * @param array $data Validated request data
     * @return User
     * @throws \Exception
     */
    public function updateNurse(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            // 1. Update User Record
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
            ];

            // Handle Profile Photo Upload
            if (isset($data['profile_photo']) && $data['profile_photo'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                
                $path = $data['profile_photo']->store('profile_photos', 'public');
                $userData['profile_photo'] = $path;
            }

            $user->update($userData);

            // 2. Update NurseProfile Record
            if ($user->nurseProfile) {
                $profileData = [
                    'is_available' => $data['is_available'],
                    'bio' => $data['bio'] ?? null,
                    'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'pincode' => $data['pincode'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'available_from' => $data['available_from'] ?? null,
                    'available_to' => $data['available_to'] ?? null,
                    'available_days' => $data['available_days'] ?? null,
                ];

                $user->nurseProfile->update($profileData);

                // 3. Sync Care Types
                if (isset($data['care_types']) && is_array($data['care_types'])) {
                    // Sync the care types with the pivot table
                    $user->nurseProfile->careTypes()->sync($data['care_types']);
                } else {
                    $user->nurseProfile->careTypes()->detach();
                }
            }

            DB::commit();

            return $user->fresh(['nurseProfile', 'nurseProfile.careTypes']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Nurse Update Failed: ' . $e->getMessage(), ['user_id' => $user->id]);
            throw $e;
        }
    }
}
