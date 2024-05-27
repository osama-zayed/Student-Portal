<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\Notifications;

class HelperController extends Controller
{
    static function NotificationsAllUser($masseg)
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $user->notify(new Notifications([
                    "body" => $masseg,
                ]));
            }
        }
    }
    static function NotificationsUserDepartment($masseg, $departmentId)
    {
        $loggedInUserId = auth()->user()->id;
        $users = User::where('user_type', '!=', 'user')
            ->where('id', '!=', $loggedInUserId)
            ->where(function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId)
                    ->orWhere('user_type', '!=', 'admin');
            })
            ->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $user->notify(new Notifications([
                    "body" => $masseg
                ]));
            }
        }
    }
    static function NotificationsAdmin($masseg)
    {
        $loggedInUserId = auth()->user()->id;
        $users = User::where('user_type', 'admin')
            ->where('id', '!=', $loggedInUserId)
            ->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $user->notify(new Notifications([
                    "body" => $masseg
                ]));
            }
        }
    }
}
