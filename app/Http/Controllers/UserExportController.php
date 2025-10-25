<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Facility;
use Carbon\Carbon;
use Excel;
use App\Exports\UserDataExport;

class UserExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display export options page
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('user.export.index', compact('user'));
    }

    /**
     * Export user data in JSON format
     */
    public function exportJson(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'include_activities' => 'boolean',
            'include_notifications' => 'boolean',
            'include_bookings' => 'boolean',
            'include_contracts' => 'boolean',
            'include_invoices' => 'boolean',
            'include_payments' => 'boolean',
            'include_comments' => 'boolean',
            'include_favorites' => 'boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);
        
        $data = $this->prepareUserData($user, $validated);
        
        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Export user data in Excel format
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'include_activities' => 'boolean',
            'include_notifications' => 'boolean',
            'include_bookings' => 'boolean',
            'include_contracts' => 'boolean',
            'include_invoices' => 'boolean',
            'include_payments' => 'boolean',
            'include_comments' => 'boolean',
            'include_favorites' => 'boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);
        
        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new UserDataExport($user, $validated), $filename);
    }

    /**
     * Export user data in CSV format
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'include_activities' => 'boolean',
            'include_notifications' => 'boolean',
            'include_bookings' => 'boolean',
            'include_contracts' => 'boolean',
            'include_invoices' => 'boolean',
            'include_payments' => 'boolean',
            'include_comments' => 'boolean',
            'include_favorites' => 'boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);
        
        $data = $this->prepareUserData($user, $validated);
        
        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $csv = $this->arrayToCsv($data);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export user data in PDF format
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'include_activities' => 'boolean',
            'include_notifications' => 'boolean',
            'include_bookings' => 'boolean',
            'include_contracts' => 'boolean',
            'include_invoices' => 'boolean',
            'include_payments' => 'boolean',
            'include_comments' => 'boolean',
            'include_favorites' => 'boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);
        
        $data = $this->prepareUserData($user, $validated);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('user.export.pdf', compact('user', 'data'));
        
        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Prepare user data for export
     */
    private function prepareUserData($user, $options)
    {
        $data = [
            'export_info' => [
                'exported_at' => now()->toISOString(),
                'exported_by' => $user->name,
                'user_id' => $user->id,
                'options' => $options,
            ],
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_picture' => $user->profile_picture,
                'bio' => $user->bio,
                'location' => $user->location,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toISOString() : null,
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toISOString() : null,
                'phone_verified_at' => $user->phone_verified_at ? $user->phone_verified_at->toISOString() : null,
                'two_factor_enabled' => $user->two_factor_enabled ?? false,
                'notification_settings' => $user->notification_settings ?? [],
                'privacy_settings' => $user->privacy_settings ?? [],
                'preferences' => $user->preferences ?? [],
                'security_settings' => $user->security_settings ?? [],
            ],
        ];
        
        // Add activities if requested
        if ($options['include_activities'] ?? false) {
            $query = ActivityLog::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['activities'] = $query->orderBy('created_at', 'desc')->get()->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'action' => $activity->action,
                    'description' => $activity->description,
                    'ip_address' => $activity->ip_address,
                    'user_agent' => $activity->user_agent,
                    'created_at' => $activity->created_at->toISOString(),
                ];
            });
        }
        
        // Add notifications if requested
        if ($options['include_notifications'] ?? false) {
            $query = Notification::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['notifications'] = $query->orderBy('created_at', 'desc')->get()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'read_at' => $notification->read_at ? $notification->read_at->toISOString() : null,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            });
        }
        
        // Add bookings if requested
        if ($options['include_bookings'] ?? false) {
            $query = Booking::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['bookings'] = $query->orderBy('created_at', 'desc')->get()->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'product_id' => $booking->product_id,
                    'facility_id' => $booking->facility_id,
                    'status' => $booking->status,
                    'start_date' => $booking->start_date,
                    'end_date' => $booking->end_date,
                    'total_amount' => $booking->total_amount,
                    'created_at' => $booking->created_at->toISOString(),
                ];
            });
        }
        
        // Add contracts if requested
        if ($options['include_contracts'] ?? false) {
            $query = Contract::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['contracts'] = $query->orderBy('created_at', 'desc')->get()->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'product_id' => $contract->product_id,
                    'facility_id' => $contract->facility_id,
                    'status' => $contract->status,
                    'start_date' => $contract->start_date,
                    'end_date' => $contract->end_date,
                    'total_amount' => $contract->total_amount,
                    'created_at' => $contract->created_at->toISOString(),
                ];
            });
        }
        
        // Add invoices if requested
        if ($options['include_invoices'] ?? false) {
            $query = Invoice::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['invoices'] = $query->orderBy('created_at', 'desc')->get()->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'contract_id' => $invoice->contract_id,
                    'status' => $invoice->status,
                    'amount' => $invoice->amount,
                    'due_date' => $invoice->due_date,
                    'paid_at' => $invoice->paid_at ? $invoice->paid_at->toISOString() : null,
                    'created_at' => $invoice->created_at->toISOString(),
                ];
            });
        }
        
        // Add payments if requested
        if ($options['include_payments'] ?? false) {
            $query = Payment::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['payments'] = $query->orderBy('created_at', 'desc')->get()->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoice_id' => $payment->invoice_id,
                    'amount' => $payment->amount,
                    'method' => $payment->method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->toISOString(),
                ];
            });
        }
        
        // Add comments if requested
        if ($options['include_comments'] ?? false) {
            $query = Comment::where('user_id', $user->id);
            
            if (isset($options['date_from'])) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if (isset($options['date_to'])) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $data['comments'] = $query->orderBy('created_at', 'desc')->get()->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'product_id' => $comment->product_id,
                    'content' => $comment->content,
                    'rating' => $comment->rating,
                    'created_at' => $comment->created_at->toISOString(),
                ];
            });
        }
        
        // Add favorites if requested
        if ($options['include_favorites'] ?? false) {
            $data['favorite_products'] = $user->favoriteProducts()->get()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => $product->type,
                    'price' => $product->price,
                    'location' => $product->location,
                    'created_at' => $product->created_at->toISOString(),
                ];
            });
            
            $data['favorite_facilities'] = $user->favoriteFacilities()->get()->map(function ($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'type' => $facility->type,
                    'location' => $facility->location,
                    'created_at' => $facility->created_at->toISOString(),
                ];
            });
        }
        
        return $data;
    }

    /**
     * Convert array to CSV format
     */
    private function arrayToCsv($data)
    {
        $csv = '';
        
        // Add export info
        $csv .= "Export Info\n";
        $csv .= "Exported At," . $data['export_info']['exported_at'] . "\n";
        $csv .= "Exported By," . $data['export_info']['exported_by'] . "\n";
        $csv .= "User ID," . $data['export_info']['user_id'] . "\n\n";
        
        // Add user info
        $csv .= "User Information\n";
        foreach ($data['user_info'] as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $csv .= $key . "," . $value . "\n";
        }
        $csv .= "\n";
        
        // Add other data sections
        $sections = ['activities', 'notifications', 'bookings', 'contracts', 'invoices', 'payments', 'comments', 'favorite_products', 'favorite_facilities'];
        
        foreach ($sections as $section) {
            if (isset($data[$section]) && !empty($data[$section])) {
                $csv .= ucfirst(str_replace('_', ' ', $section)) . "\n";
                
                // Add headers
                $firstItem = $data[$section][0];
                if (is_array($firstItem)) {
                    $csv .= implode(',', array_keys($firstItem)) . "\n";
                    
                    // Add data rows
                    foreach ($data[$section] as $item) {
                        $csv .= implode(',', array_map(function($value) {
                            return is_array($value) ? json_encode($value) : $value;
                        }, $item)) . "\n";
                    }
                }
                $csv .= "\n";
            }
        }
        
        return $csv;
    }

    /**
     * Get export statistics
     */
    public function getExportStats()
    {
        $user = Auth::user();
        
        $stats = [
            'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
            'total_notifications' => Notification::where('user_id', $user->id)->count(),
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'total_contracts' => Contract::where('user_id', $user->id)->count(),
            'total_invoices' => Invoice::where('user_id', $user->id)->count(),
            'total_payments' => Payment::where('user_id', $user->id)->count(),
            'total_comments' => Comment::where('user_id', $user->id)->count(),
            'favorite_products_count' => $user->favoriteProducts()->count(),
            'favorite_facilities_count' => $user->favoriteFacilities()->count(),
        ];
        
        return response()->json($stats);
    }
}
