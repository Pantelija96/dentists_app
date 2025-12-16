<?php

namespace App\Http\Controllers;

use App\Mail\UserApproved;
use App\Models\Notification;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public $data = [];

    public function dashboard(){
        $this->data['work_orders'] = WorkOrder::with(['user', 'status'])
            ->where('user_id', auth()->id())
            ->get();
        $this->data['statuses'] = WorkOrderStatus::all();
        return view('user.dashboard', $this->data);
    }

    public function approve($id)
    {
        $user = User::with('registrationRequests')->findOrFail($id);
        $registrationPending = $user->registrationRequests;

        DB::transaction(function () use ($user, $registrationPending) {
            $user->update([
                'is_approved' => true
            ]);

            $registrationPending->update([
                'reviewed_by' => auth()->id(),
                'status' => 1
            ]);
        });

        event(new UserApproved(
            user: $user
        ));

        return redirect()->back()->with('success', "Successfully approved user: {$user->first_name} {$user->last_name}!");
    }

    public function deny($id){
        $user = User::with('registrationRequests')->findOrFail($id);
        $registrationPending = $user->registrationRequests;

        DB::transaction(function () use ($user, $registrationPending) {
            $user->update([
                'is_approved' => false
            ]);

            $registrationPending->update([
                'reviewed_by' => auth()->id(),
                'status' => 3
            ]);
        });

        return redirect()->back()->with('info', "You have just denied user: {$user->first_name} {$user->last_name}, they will stay in data base!");
    }

    public function destroy($id){
        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->delete();
            $user->update([
                'email' => null
            ]);
        });

        return redirect()->back()->with('info', "User {$user->first_name} {$user->last_name} has been deleted successfully.");
    }

}
