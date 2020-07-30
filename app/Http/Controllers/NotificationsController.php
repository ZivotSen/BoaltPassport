<?php
/**
 * Created by PhpStorm.
 * User: ageorge
 * Date: 7/29/2020
 * Time: 12:19 PM
 */

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// CRUD to manage notifications
class NotificationsController extends Controller
{
    // Create a notification for a logged user
    public function create(Request $request){
        $user = Auth::user();
        $notification = new Notification();
        if($user){
            $input = $request->all();
            $input['user_id'] = $user->id;
            if(!key_exists('notification', $input)){
                return response()->json([
                    'error' => "Notification needs that you send the 'notification' field."
                ]);
            }

            try {
                $notification->fill($input);
                if(is_null($notification->notified) || empty($notification->notified)){
                    $notification->notified = false;
                }
                $notification->save();
                return response()->json($notification);
            } catch (\Exception $e){
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'error' => "You can't access this service."
        ]);
    }

    // Get all notifications for a logged user
    public function read(){
        $user = Auth::user();
        if($user){
            $notifications = Notification::where('user_id', $user->id)->get();
            return response()->json([
                'notifications' => $notifications
            ]);
        }

        return response()->json([
            'error' => "You can't access this service."
        ]);
    }

    // Update a notification.
    // (This can be used to update the (unread/read) status too)
    public function update(Request $request){
        $user = Auth::user();
        if($user){
            $input = $request->all();
            if(!key_exists('notification_id', $input) || empty($input['notification_id'])){
                return response()->json([
                    'error' => "Parameter needed notification_id to update your notification."
                ]);
            }

            $notification = Notification::where('id', $input['notification_id'])->first();
            if($notification){
                if(!$this->isModifiableNotification($notification)){
                    return response()->json([
                        'error' => "Can't modify this notification because you're not the owner."
                    ]);
                }

                try {
                    $notification->fill($input);
                    $notification->save();
                    return response()->json([
                        'notification' => $notification
                    ]);
                } catch (\Exception $e){
                    return response()->json([
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'error' => "No notification found for requested notification_id."
            ]);
        }

        return response()->json([
            'error' => "You can't access this service."
        ]);
    }

    public function delete(Request $request){
        $user = Auth::user();
        if($user){
            $input = $request->all();
            if(!key_exists('notification_id', $input) || empty($input['notification_id'])){
                return response()->json([
                    'error' => "Parameter needed notification_id to update your notification."
                ]);
            }

            $notification = Notification::where('id', $input['notification_id'])->first();
            if($notification){
                if(!$this->isModifiableNotification($notification)){
                    return response()->json([
                        'error' => "Can't remove this notification because you're not the owner."
                    ]);
                }

                try {
                    $notification->delete();
                    return response()->json([
                        'message' => "Notification deleted successfully."
                    ]);
                } catch (\Exception $e){
                    return response()->json([
                        'error' => $e->getMessage()
                    ]);
                }
            }

        }

        return response()->json([
            'error' => "You can't access this service."
        ]);
    }

    private function isModifiableNotification($notification){
        $user = Auth::user();
        if($notification->user_id == $user->id){
            return true;
        }
        return false;
    }
}
