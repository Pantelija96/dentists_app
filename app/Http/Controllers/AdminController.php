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
        $this->data['statuses'] = WorkOrderStatus::all();

        return view('admin.dashboard', $this->data);
    }

    public function showUsersWorkOrders($id){
        $this->data['work_orders'] = WorkOrder::with(['user', 'status'])
            ->where('deleted', false)
            ->where('draft', false)
            ->where('user_id', $id)
            ->whereHas('user', function ($q) {
                $q->where('region_id', auth()->user()->region_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $this->data['statuses'] = WorkOrderStatus::all();
        $this->data['target_user'] = User::where('id', $id)->first();

        return view('admin.user-work-orders', $this->data);
    }
}
