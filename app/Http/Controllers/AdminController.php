<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderStatus;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public $data = [];

    public function dashboard()
    {
        $this->data['pendingRegistration'] = User::where([
            'is_approved' => false,
            'region_id' => auth()->user()->region_id
        ])->with('registrationRequests')->get();

        $this->data['work_orders'] = WorkOrder::with(['user', 'status'])
            ->where('deleted', false)
            ->where('draft', false)
            ->whereHas('user', function ($q) {
                $q->where('region_id', auth()->user()->region_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if (auth()->user()->number_of_notifications > 0) {
            $this->data['notifications'] = Notification::where('region_id', auth()->user()->region_id)
                ->latest()
                ->limit(auth()->user()->number_of_notifications)
                ->get();
        }
        $this->data['statuses'] = WorkOrderStatus::all();

        return view('admin.dashboard', $this->data);
    }
}
