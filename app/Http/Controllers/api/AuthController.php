<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\Student as users;
use App\Notifications\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalAccessToken;

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
                'academic_id' => 'required|integer',
                'password' => 'required|string|max:255',
            ], [
                'academic_id.required' => 'رقم الطالب الاكاديمي مطلوب',
                'password.required' => 'ادخل الرمز',
                'password.max' => 'الحد الاقصى للرمز 255',
            ]);

            //  تسجيل الدخول
            if (!$token = auth('api')->attempt([
                "academic_id" => $data["academic_id"],
                "password" => $data["password"],
            ])) {
                return response()->json([
                    'Status' => false,
                    'Message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
                ], 400);
            }

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
                'Message' => 'حدث خطاء اثناء تسجيل الدخول'
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
                'Old_password' => 'required|string|max:255',
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
            $userToUpdate = users::findOrFail(auth("api")->user()->id);
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
            'name' => $user->full_name,
            'personal_id' => $user->personal_id,
            'academic_id' => $user->academic_id,
            'phone_number' => $user->phone_number,
            'relative_phone_number' => $user->relative_phone_number,
            'gender' => $user->gender,
            'nationality' => $user->nationality,
            'date_of_birth' => $user->date_of_birth,
            'place_of_birth' => $user->place_of_birth,
            'educational_qualification' => $user->educational_qualification,
            'high_school_grade' => $user->high_school_grade,
            'school_graduation_date' => $user->school_graduation_date,
            'discount_percentage' => $user->discount_percentage,
            'college' => $user->college->name,
            'college_id' => $user->college_id,
            'specialization' => $user->specialization->name,
            'specialization_id' => $user->specialization_id,
            'semester_num' => $user->semester_num,
            'user_status' => $user->user_status,
            'created_at' => $user->created_at->format('Y-m-d'),
            'image' => asset($user->image),
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['Status' => true, 'Message' => 'تم تسجيل الخروج بنجاح']);
    }

    function createPersonalAccessToken($userId, $accessToken)
    {
        PersonalAccessToken::create([
            'tokenable_id' => $userId,
            'tokenable_type' => 'App\Models\Student',
            'name' => 'access_token',
            'token' => $accessToken,
        ]);
    }
    protected function respondWithToken($token)
    {
        if (auth('api')->user()->user_status) {
            $userId = auth('api')->user()->id;

            // $this->createPersonalAccessToken($userId, $token);
            return response()->json([
                'Status' => true,
                'Message' => "تم تسجيل الدخول بنجاح",
                'access_token' => $token,
                'token_type' => 'bearer',
                'name' => auth('api')->user()->full_name,
                'user_status' => auth('api')->user()->user_status,
            ]);
        } else {
            return response()->json(['Status' => false, 'Message' => 'حسابك موقف'], 422);
        }
    }

   
}
