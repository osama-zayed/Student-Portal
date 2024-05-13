<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User as users;
use App\Notifications\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('userStatus', ['except' => ['login']]);
    }

    public function login()
    {
        try {
            $data = request()->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|max:255',
            ], [
                'username.required' => 'اسم المستخدم الفريد مطلوب',
                'password.required' => 'ادخل الرمز',
                'password.max' => 'الحد الاقصى للرمز 255',
                'username.max' => 'الحد الاقصى للاسم الفريد 255',
            ]);
            $rateLimiter = app(RateLimiter::class);
            $rateLimiter->hit($data["username"]);

            if ($rateLimiter->tooManyAttempts($data["username"], 5)) {
                return response()->json([
                    'Status' => false,
                    'Message' => "لقد قمت بمحاولة تسجيل الدخول عددًا كبيرًا من المرات. يرجى الانتظار لمدة دقيقة قبل المحاولة مرة أخرى."
                ], 400);
            }

            //  تسجيل الدخول
            if (!$token = auth('api')->attempt([
                "username" => htmlspecialchars(strip_tags($data["username"])),
                "password" => htmlspecialchars(strip_tags($data["password"])),
            ])) {
                return response()->json([
                    'Status' => false,
                    'Message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
                ], 400);
            }
            // if (!$token = auth('api')->attempt([
            //     "username" => htmlspecialchars(strip_tags($data["username"])),
            //     "password" => htmlspecialchars(strip_tags($data["password"])),
            // ], ['table' => 'اسم_الجدول_الجديد'])) {
            //     return response()->json([
            //         'Status' => false,
            //         'Message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
            //     ], 400);
            // }
            
            return $this->respondWithToken($token);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'Status' => false,
                'Message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
            ], 400);
        }
    }


    public function notification()
    {
        try {
            $user = Auth::user();
            $notifications = $user->notifications()->take(2000)->get()->toArray();
            $data = [];
            foreach ($notifications as $notification) {
                $notificationData = [
                    "description" => $notification['data']['data'],
                    "created_at" => date('H:i Y-m-d', strtotime($notification['created_at'])),
                ];
                $data[] = $notificationData;
            }
            if (empty($data)) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد اشعارات'], 404);
            }

            return response()->json(['Status' => true, 'Message' => 'تم جلب البيانات', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }

    public function editUser()
    {

        try {
            $credentials = request()->validate([
                'Old_password' => 'required|string|min:8|max:255',
                'New_password' => 'required|string|min:8|confirmed|max:255',
            ], [
                "Old_password.required" => "ادخل الرمز القديم",
                "Old_password.min" => " اقل عدد للرمز القديم 8 خانات",
                "Old_password.max" => "الحد الاقصى للرمز 255",
                "New_password.required" => "ادخل الرمز الجديد",
                "New_password.min" => " اقل عدد للرمز القديم 8 خانات",
                "New_password.max" => "الحد الاقصى للرمز 255",
                "New_password.confirmed" => "الرمز الجديد غير متطابق",
            ]);

            // البحث عن المستخدم المراد تعديله
            $userToUpdate = User::findOrFail(auth("api")->user()->id);
            // تحديث خصائص المستخدم
            $oldPasswordFromDatabase = $userToUpdate->password;
            $oldPasswordEntered = htmlspecialchars(strip_tags($credentials['Old_password']));
            if (!password_verify($oldPasswordEntered, $oldPasswordFromDatabase)) {
                return response()->json(['Status' => false, "Message" => "الرمز القديم غير صحيح"], 400);
            }
            $newPassword = htmlspecialchars(strip_tags($credentials['New_password']));
            $userToUpdate->password = bcrypt($newPassword);
            if (request()->has('password')) {
                $userToUpdate->password = bcrypt(htmlspecialchars(strip_tags($credentials['password'])));
            }
            // التحقق من نجاح التحديث
            if ($userToUpdate->update()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = users::find(auth("api")->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                $user->notify(new Notifications([
                    "body" => " لقد قمت بتعديل بياناتك الشخصية "  .
                        "في الوقت والتاريخ " . $date,
                ]));
                activity()->performedOn($userToUpdate)->event("تعديل مستخدم")->causedBy($user)
                    ->log(
                        " لقد قام المستخدم " . $userToUpdate->name . "بتعديل بياناتة الشخصية" .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                return response()->json(['Status' => true, "Message" => "تم التعديل بنجاح"]);
            } else {
                return response()->json(['Status' => false, "Message" => "خطأ عند التعديل"], 500);
            }
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (Exception $e) {
            // إدارة استثناء عدم وجود المستخدم
            return response()->json(['Status' => false, "Message" => "المستخدم غير موجود"], 404);
        }
    }

    public function me()
    {
        $user = auth('api')->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'phone_number' => $user->phone_number
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        session()->forget('user_id');
        return response()->json(['Status' => true, 'Message' => 'تم تسجيل الخروج بنجاح']);
    }

    protected function respondWithToken($token)
    {
        if (auth('api')->user()->user_status) {
            session()->put('user_id', auth('api')->user()->id);
            return response()->json([
                'Status' => true,
                'Message' => "تم تسجيل الدخول بنجاح",
                'access_token' => $token,
                'token_type' => 'bearer',
                'name' => auth('api')->user()->name,
                'user status' => auth('api')->user()->user_status,
            ]);
        } else return response()->json(['Status' => false, 'Message' => 'حسابك موقف'], 422);
    }
}
