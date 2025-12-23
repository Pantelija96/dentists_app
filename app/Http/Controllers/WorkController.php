<?php

namespace App\Http\Controllers;

use App\Events\NewCommentEvent;
use App\Events\NewWorkOrderEvent;
use App\Events\WorkOrderCreated;
use App\Events\WorkOrderStatusChange;
use App\Events\WorkOrderStatusUpdatedEvent;
use App\Models\Notification;
use App\Models\Parameter;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderComment;
use App\Models\WorkOrderFile;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderStatus;
use App\Models\WorkType;
use App\Models\WorkTypeMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    private $data = [];

    public function showAddNew(){
        $this->data['work_types'] = WorkType::all();
        if(auth()->user()->role == 'admin'){
            $this->data['users'] = User::where([
                'region_id' => auth()->user()->region_id,
                'deleted_at' => null,
                'role' => 'user'
            ])->get();
        }
        if (auth()->user()->number_of_notifications > 0) {
            if(auth()->user()->role == 'user') {
                $this->data['notifications'] = Notification::where('user_id', auth()->user()->id)
                    ->latest()
                    ->limit(auth()->user()->number_of_notifications)
                    ->get();
            }
            else{
                $this->data['notifications'] = Notification::where('region_id', auth()->user()->region_id)
                    ->latest()
                    ->limit(auth()->user()->number_of_notifications)
                    ->get();
            }
        }
        return view('work.addNew', $this->data);
    }

    public function inspect($id)
    {
        $this->data['work_order'] = WorkOrder::with([
            'status',
            'items.workType',
            'items.material',
            'comments.user'
        ])->where('id', $id)->firstOrFail();

        if (auth()->user()->role == 'admin') {
            $this->data['statuses'] = WorkOrderStatus::all();
        }

        if (auth()->user()->number_of_notifications > 0) {
            if(auth()->user()->role == 'user') {
                $this->data['notifications'] = Notification::where('user_id', auth()->user()->id)
                    ->latest()
                    ->limit(auth()->user()->number_of_notifications)
                    ->get();
            }
            else{
                $this->data['notifications'] = Notification::where('region_id', auth()->user()->region_id)
                    ->latest()
                    ->limit(auth()->user()->number_of_notifications)
                    ->get();
            }
        }

        return view('work.inspect', $this->data);
    }

    public function store(Request $request){
        $request->validate([
            'work_name' => 'required|string|max:255'
        ]);

        $draft = $request->action == 'draft';

        DB::beginTransaction();

        try {

            $workOrder = WorkOrder::create([
                'user_id' => auth()->user()->role == 'user' ? auth()->id() : $request->work_user,
                'status_id' => 1,
                'delivery_option_id' => 1,
                'name' => $request->work_name,
                'finished' => false,
                'total_price' => 0,
                'draft' => $draft
            ]);

            $groups = json_decode($request->groups_payload, true);
            foreach ($groups as $group) {
                $parameters = $group['parameters'] ?? [];

                $parametersFixed = [];
                foreach ($parameters as $paramId => $value) {
                    $paramName = Parameter::find($paramId)?->name ?? "parameter{$paramId}";
                    $parametersFixed[$paramName] = $value;
                }

                foreach ($group['teeth'] as $toothNumber) {
                    WorkOrderItem::create([
                        'work_order_id' => $workOrder->id,
                        'work_type_id' => $group['typeOfWorkId'],
                        'material_id' => $group['materialId'],
                        'tooth_number' => $toothNumber,
                        'parameters' => json_encode($parametersFixed),
                        'price' => $group['price'] ?? 0,
                    ]);
                }
            }

            if ($request->hasFile('uploads')) {
                foreach ($request->file('uploads') as $index => $file) {
                    if (!$file || !$file->isValid()) continue;

                    $path = $file->store('work_order_files', 'public');

                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'file_type' => $file->extension(),
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();



            if(auth()->user()->role == 'user')
            {
                if($draft){
                    return redirect('/user/dashboard')->with('success', 'Work order draft saved successfully.');
                }
                else{
                    event(new NewWorkOrderEvent($workOrder));
                    return redirect('/user/dashboard')->with('success', 'Work order saved successfully.');
                }
            }
            else
            {
                return redirect('/admin/dashboard')->with('success', 'Work order saved successfully.');
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving work order: '.$e->getMessage());
        }
    }

    public function downloadAll($id){
        $workOrder = WorkOrder::findOrFail($id);
        $files = WorkOrderFile::where('work_order_id', $id)->get();
        $user = auth()->user();

        if ( $workOrder->user_id !== $user->id && $user->role !== 'admin' && $user->role !== 'super-admin')  {
            return back()->with('error', "You do not have permission to download these files.");
        }

        if ($files->isEmpty()) {
            return back()->with('error', 'No files attached to this work order.');
        }

        $zipFileName = "work_order_{$id}_files.zip";
        $zipPath = storage_path("app/public/tmp/{$zipFileName}");

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {

            foreach ($files as $file) {
                $fullPath = storage_path("app/public/" . $file->file_path);

                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($fullPath));
                }
            }

            $zip->close();
        } else {
            return back()->with('error', 'Could not create ZIP file.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function addcomment(WorkOrder $work_order, Request $request){
        $work_order_comment = WorkOrderComment::create([
            'user_id' => auth()->user()->id,
            'work_order_id' => $work_order->id,
            'comment' => $request->get('comment'),
        ]);

        if($work_order_comment){
            event(new NewCommentEvent($work_order_comment));
            return redirect()->back()->with('success', "Successfully created new comment!");
        } else {
            return redirect()->back()->with('error', 'Some unexpected error occurred, error id: error-5!');
        }
    }

    public function changestatus(WorkOrder $work_order, Request $request){
        $status = WorkOrderStatus::where('id', $request->get('status'))->first();

        $updated = $work_order->update([
            'status_id' => $request->get('status'),
            'locked' => $status->lock_work_order
        ]);

        if($updated){
            event(new WorkOrderStatusUpdatedEvent($work_order));
            return redirect()->back()->with('success', "Successfully updated status!");
        } else {
            return redirect()->back()->with('error', 'Some unexpected error occurred, error id: error-4!');
        }
    }

    public function remove(WorkOrder $workOrder){
        $updated = $workOrder->update([
            'deleted' => !$workOrder->deleted
        ]);

        if($updated){
            if($workOrder->deleted){
                return redirect()->back()->with('info', "Successfully deleted work order!");
            }
            else{
                return redirect()->back()->with('success', "Successfully restored work order!");
            }
        } else {
            return redirect()->back()->with('error', 'Some unexpected error occurred, error id: error-4!');
        }
    }

    public function edit($id)
    {
        $work = WorkOrder::with([
            'files',
            'user',
            'items.workType',
            'items.material',
        ])->findOrFail($id);

        $this->data['work'] = $work;
        $this->data['work_types'] = WorkType::where('deleted', false)->get();

        return view('work.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'work_name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $workOrder = WorkOrder::findOrFail($id);

            $workOrder->update([
                'name' => $request->work_name,
                'draft' => $request->action === 'draft',
            ]);

            WorkOrderItem::where('work_order_id', $workOrder->id)->delete();

            $groups = json_decode($request->groups, true);

            if (!is_array($groups)) {
                throw new \Exception('Invalid groups payload');
            }

            foreach ($groups as $group) {

                $parametersFixed = [];

                if (!empty($group['parameters'])) {
                    foreach ($group['parameters'] as $paramId => $value) {
                        $label = $group['parameterLabels'][$paramId] ?? "Param {$paramId}";
                        $parametersFixed[$label] = $value;
                    }
                }

                foreach ($group['teeth'] as $toothNumber) {
                    WorkOrderItem::create([
                        'work_order_id' => $workOrder->id,
                        'work_type_id'  => $group['typeOfWorkId'],
                        'material_id'   => $group['materialId'],
                        'tooth_number'  => $toothNumber,
                        'parameters'    => json_encode($parametersFixed),
                        'price'         => $group['price'] ?? 0,
                    ]);
                }
            }

            $deletedUploads = json_decode($request->deleted_uploads ?? '[]', true);
            if (!empty($deletedUploads)) {
                WorkOrderFile::whereIn('id', $deletedUploads)->delete();
            }

            if ($request->hasFile('uploads')) {
                foreach ($request->file('uploads') as $file) {
                    if (!$file || !$file->isValid()) continue;

                    $path = $file->store('work_order_files', 'public');

                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'file_type' => $file->extension(),
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            if(auth()->user()->role == 'user')
            {
                return redirect('/user/dashboard')->with('success', 'Work order updated successfully.');
            }
            else
            {
                return redirect('/admin/dashboard')->with('success', 'Work order updated successfully.');
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function publish(WorkOrder $workOrder){
        $updated = $workOrder->update([
            'draft' => false
        ]);

        if($updated){
            return redirect()->back()->with('info', "Successfully published work order!");
        } else {
            return redirect()->back()->with('error', 'Some unexpected error occurred, error id: error-4!');
        }
    }
}
