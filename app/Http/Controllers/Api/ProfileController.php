<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateFcmTokenRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        $profile = $this->profileService->getProfile($request->user()->id);

        return $this->successResponse(new ProfileResource($profile));
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = $this->profileService->updateProfile($request->user()->id, $request->validated());

        return $this->successResponse(new ProfileResource($profile), 'تم تحديث البيانات بنجاح');
    }

    public function updateFcmToken(UpdateFcmTokenRequest $request)
    {
        $this->profileService->updateFcmToken($request->user()->id, $request->fcm_token);

        return $this->successResponse(null, 'تم تحديث التوكن بنجاح');
    }

    public function destroy(Request $request)
    {
        $this->profileService->deleteAccount($request->user()->id);

        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return $this->successResponse(null, 'تم حذف الحساب بنجاح');
    }

    /**
     * حظر مستخدم من قبل الأدمن (لا يتم حذفه، فقط منع الدخول)
     */
    public function banUser(Request $request, int $id)
    {
        $targetUser = \App\Models\User::find($id);

        if (!$targetUser) {
            return $this->errorResponse('المستخدم غير موجود', 404);
        }

        if ($targetUser->role === 'admin') {
            return $this->errorResponse('لا يمكن حظر حساب أدمن', 403);
        }

        if ($targetUser->id === $request->user()->id) {
            return $this->errorResponse('لا يمكنك حظر حسابك أنت', 403);
        }

        $this->profileService->banUser($id);

        return $this->successResponse(null, 'تم حظر المستخدم بنجاح');
    }

    /**
     * إلغاء حظر مستخدم من قبل الأدمن
     */
    public function unbanUser(Request $request, int $id)
    {
        $targetUser = \App\Models\User::find($id);

        if (!$targetUser) {
            return $this->errorResponse('المستخدم غير موجود', 404);
        }

        $this->profileService->unbanUser($id);

        return $this->successResponse(null, 'تم إلغاء حظر المستخدم بنجاح');
    }
}
