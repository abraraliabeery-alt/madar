<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Booking;
use Carbon\Carbon;

class FacilityTaskController extends Controller
{
    /**
     * Display a listing of tasks related to the facility (created by or assigned to facility users).
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $facilityUserIds = $facility->users()->pluck('users.id');

        $query = Task::query()
            ->whereIn('created_by', $facilityUserIds)
            ->orWhereHas('users', function ($q) use ($facilityUserIds) {
                $q->whereIn('users.id', $facilityUserIds);
            })
            ->with(['users', 'createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assignee_id')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('users.id', $request->assignee_id);
            });
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();
        $assignees = $facility->users()->get(['users.id','users.name','users.email']);

        // HR/Tasks KPIs (non-disruptive, computed only)
        $today = Carbon::today();
        $base = Task::query()
            ->whereIn('created_by', $facilityUserIds)
            ->orWhereHas('users', function ($q) use ($facilityUserIds) {
                $q->whereIn('users.id', $facilityUserIds);
            });

        $kpis = [
            'total' => (int) (clone $base)->count(),
            'open' => (int) (clone $base)->whereIn('status', ['open','assigned','in_progress'])->count(),
            'done_week' => (int) (clone $base)->where('status','done')->where('updated_at','>=', now()->startOfWeek())->count(),
            'overdue' => (int) (clone $base)->whereDate('deadline','<',$today)->whereNotIn('status',['done','cancelled'])->count(),
            'due_today' => (int) (clone $base)->whereDate('deadline','=',$today)->whereNotIn('status',['done','cancelled'])->count(),
        ];

        // Workload per assignee (open tasks)
        $workload = [];
        foreach ($assignees as $a) {
            $count = (int) Task::whereIn('created_by', $facilityUserIds)
                ->orWhereHas('users', function($q) use ($a){ $q->where('users.id', $a->id); })
                ->whereIn('status', ['open','assigned','in_progress'])
                ->count();
            $workload[] = ['id'=>$a->id,'name'=>$a->name,'open_tasks'=>$count];
        }

        return view('facility.tasks.index', compact('tasks','assignees','kpis','workload'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }
        $assignees = $facility->users()->get(['users.id','users.name']);
        return view('facility.tasks.create', compact('assignees'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:maintenance,cleaning,inspection,visit,other',
            'status' => 'required|string|in:open,assigned,in_progress,done,cancelled',
            'priority' => 'required|string|in:low,medium,high',
            'deadline' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'integer|exists:users,id',
        ]);

        $task = Task::create([
            'type' => $request->type,
            'status' => $request->status,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'created_by' => Auth::id(),
        ]);

        if ($request->filled('assignees')) {
            $task->users()->sync($request->assignees);
        }

        return redirect()->route('facility.tasks.index')->with('success', 'تم إنشاء المهمة بنجاح');
    }

    /**
     * Quick create a task from index with minimal inputs.
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'status' => 'nullable|string|in:open,assigned,in_progress,done,cancelled',
            'priority' => 'nullable|string|in:low,medium,high',
            'deadline' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'integer|exists:users,id',
        ]);

        $task = Task::create([
            'type' => $request->type,
            'status' => $request->input('status', 'open'),
            'priority' => $request->input('priority', 'medium'),
            'deadline' => $request->deadline,
            'created_by' => Auth::id(),
        ]);

        if ($request->filled('assignees')) {
            $task->users()->sync($request->assignees);
        }

        return redirect()->route('facility.tasks.index')->with('success', 'تمت إضافة مهمة سريعة');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }
        $assignees = $facility->users()->get(['users.id','users.name']);
        $task->load('users');
        return view('facility.tasks.edit', compact('task','assignees'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'type' => 'required|string|in:maintenance,cleaning,inspection,visit,other',
            'status' => 'required|string|in:open,assigned,in_progress,done,cancelled',
            'priority' => 'required|string|in:low,medium,high',
            'deadline' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'integer|exists:users,id',
        ]);

        $task->update([
            'type' => $request->type,
            'status' => $request->status,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
        ]);

        $task->users()->sync($request->assignees ?? []);

        return redirect()->route('facility.tasks.index')->with('success', 'تم تحديث المهمة بنجاح');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('facility.tasks.index')->with('success', 'تم حذف المهمة بنجاح');
    }

    /**
     * Generate reminder tasks (no DB schema changes):
     * - Overdue invoices
     * - Due today invoices
     * - Today's bookings
     */
    public function generateReminders(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.tasks.index')->with('error', 'لا توجد منشأة مرتبطة');
        }

        $today = Carbon::today();
        $created = 0;

        // Overdue invoices (due_date < today and unpaid)
        if (method_exists($facility, 'invoices')) {
            $overdue = $facility->invoices()
                ->whereDate('due_date', '<', $today)
                ->where(function($q){ $q->whereNull('paid_at')->orWhere('status','!=','paid'); })
                ->limit(200)
                ->get();

            foreach ($overdue as $inv) {
                $type = 'تذكير فاتورة متأخرة #' . $inv->id;
                if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                    Task::create([
                        'type' => $type,
                        'status' => 'open',
                        'priority' => 'high',
                        'deadline' => $today,
                        'created_by' => Auth::id(),
                    ]);
                    $created++;
                }
            }

            // Due today invoices
            $dueToday = $facility->invoices()
                ->whereDate('due_date', '=', $today)
                ->where(function($q){ $q->whereNull('paid_at')->orWhere('status','!=','paid'); })
                ->limit(200)
                ->get();

            foreach ($dueToday as $inv) {
                $type = 'تذكير فاتورة مستحقة اليوم #' . $inv->id;
                if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                    Task::create([
                        'type' => $type,
                        'status' => 'open',
                        'priority' => 'medium',
                        'deadline' => $today,
                        'created_by' => Auth::id(),
                    ]);
                    $created++;
                }
            }
        }

        // Today's bookings
        if (method_exists($facility, 'bookings')) {
            $bookingsToday = $facility->bookings()
                ->whereDate('date', $today)
                ->limit(200)
                ->get();

            foreach ($bookingsToday as $bk) {
                $type = 'متابعة حجز اليوم #' . $bk->id;
                if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                    Task::create([
                        'type' => $type,
                        'status' => 'open',
                        'priority' => 'medium',
                        'deadline' => $today,
                        'created_by' => Auth::id(),
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('facility.tasks.index')->with('success', 'تم توليد ' . $created . ' مهام تذكير');
    }
}
