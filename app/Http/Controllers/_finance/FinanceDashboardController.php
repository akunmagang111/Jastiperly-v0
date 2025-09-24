<?php

namespace App\Http\Controllers\_finance;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BuyTransaction;
use App\Models\SendTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceDashboardController extends Controller
{

    public function index(Request $request)
    {
        $range = $request->query('range', 'today');
        $end = Carbon::now();
        $start = null;

        switch ($range) {
            case 'daily':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end   = Carbon::now()->endOfDay();
                break;

            case 'weekly':
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                break;

            case 'monthly':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfMonth();
                break;

            case 'yearly':
                $start = Carbon::now()->subYears(4)->startOfYear();
                $end   = Carbon::now()->endOfYear();
                break;

            default:
                $start = Carbon::today();
                $end   = Carbon::now();
        }

        // Hitung total card sesuai range
        $sendCount = DB::table('send_transactions')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $buyCount = DB::table('buy_transactions')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $income = DB::table('buy_transactions')
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'approved')
            ->sum('total_price');

        $todayIncome = DB::table('buy_transactions')
            ->whereDate('created_at', Carbon::today())
            ->where('payment_status', 'approved')
            ->sum('total_price');

        $monthIncome = DB::table('buy_transactions')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'approved')
            ->sum('total_price');

        // ============================
        // TOP USER bulan ini
        // ============================
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $buyThisMonth = DB::table('buy_transactions')
            ->select(DB::raw('buyer_id as user_id'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->groupBy('buyer_id');

        $sendThisMonth = DB::table('send_transactions')
            ->select(DB::raw('sender_id as user_id'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->groupBy('sender_id');

        $union = $buyThisMonth->unionAll($sendThisMonth);

        $topUsersMonth = DB::table(DB::raw("({$union->toSql()}) as t"))
            ->mergeBindings($union)
            ->select('t.user_id', DB::raw('SUM(total) as total_trx'))
            ->groupBy('t.user_id')
            ->orderByDesc('total_trx')
            ->limit(5)
            ->get();

        $topUsersThisMonth = $topUsersMonth->map(function ($row) {
            $user = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.id', $row->user_id)
                ->select('users.id', 'users.name', 'user_details.account_image')
                ->first();

            return [
                'user_id'   => $row->user_id,
                'name'      => $user->name ?? 'Unknown',
                'image'     => $user->account_image
                    ? asset('storage/'.$user->account_image)
                    : asset('assets/images/user-placeholder.png'),
                'total_trx' => $row->total_trx,
            ];
        });

        // Top user sesuai range (pakai withCount bawaan)
        $topUsers = \App\Models\User::withCount([
            'buyTransactions as total_buy' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            },
            'sendTransactions as total_send' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            },
        ])
        ->orderByRaw('(COALESCE(total_buy,0) + COALESCE(total_send,0)) DESC')
        ->paginate(10);

        // Latest 5 users
        $latestUsers = DB::table('users')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // =======================
        // Data chart per interval
        // =======================
        $chartLabels = [];
        $chartSendData = [];
        $chartBuyData = [];
        $weekTooltips = [];

        switch($range) {
            case 'daily':
                for ($d = 0; $d < 7; $d++) {
                    $day = Carbon::now()->subDays(6 - $d);
                    $chartLabels[] = $day->format('D');
                    $chartSendData[] = DB::table('send_transactions')
                        ->whereDate('created_at', $day->toDateString())
                        ->count();
                    $chartBuyData[] = DB::table('buy_transactions')
                        ->whereDate('created_at', $day->toDateString())
                        ->count();
                }
                break;

            case 'weekly':
                $period = new \Carbon\CarbonPeriod($start, '1 week', $end);
                foreach ($period as $startOfWeek) {
                    $endOfWeek = $startOfWeek->copy()->endOfWeek();
                    if ($endOfWeek->month != $startOfWeek->month) {
                        $endOfWeek = Carbon::now()->endOfMonth();
                    }

                    $chartLabels[] = 'Week ' . $startOfWeek->weekOfMonth;
                    $weekTooltips[] = $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M');

                    $chartSendData[] = DB::table('send_transactions')
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->count();
                    $chartBuyData[] = DB::table('buy_transactions')
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->count();
                }
                break;

            case 'monthly':
                for ($m = 1; $m <= Carbon::now()->month; $m++) {
                    $chartLabels[] = Carbon::create(null, $m)->format('M');
                    $chartSendData[] = DB::table('send_transactions')
                        ->whereMonth('created_at', $m)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->count();
                    $chartBuyData[] = DB::table('buy_transactions')
                        ->whereMonth('created_at', $m)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->count();
                }
                break;

            case 'yearly':
                $currentYear = Carbon::now()->year;
                for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
                    $chartLabels[] = $y;
                    $chartSendData[] = DB::table('send_transactions')
                        ->whereYear('created_at', $y)
                        ->count();
                    $chartBuyData[] = DB::table('buy_transactions')
                        ->whereYear('created_at', $y)
                        ->count();
                }
                break;
        }

        // Transaksi terbaru hari ini (buy + send)
        $buyTransactions = DB::table('buy_transactions')
            ->join('users as buyers', 'buy_transactions.buyer_id', '=', 'buyers.id')
            ->join('user_details as buyer_details', 'buyers.id', '=', 'buyer_details.user_id')
            ->join('users as travelers', 'buy_transactions.traveler_id', '=', 'travelers.id')
            ->join('payment_methods', 'buy_transactions.payment_method_id', '=', 'payment_methods.id')
            ->whereDate('buy_transactions.created_at', Carbon::today())
            ->select(
                'buy_transactions.id as transaction_id',
                'buyers.name as buyer_name',
                'buyer_details.account_image as buyer_image',
                'travelers.name as traveler_name',
                'buy_transactions.total_price',
                DB::raw("'Beli Produk' as service"),
                'payment_methods.type as payment_method',
                'buy_transactions.payment_status as status',
                'buy_transactions.created_at'
            );

        $sendTransactions = DB::table('send_transactions')
            ->join('users as senders', 'send_transactions.sender_id', '=', 'senders.id')
            ->join('user_details as sender_details', 'senders.id', '=', 'sender_details.user_id')
            ->join('users as receivers', 'send_transactions.reciever_id', '=', 'receivers.id')
            ->join('payment_methods', 'send_transactions.payment_method_id', '=', 'payment_methods.id')
            ->whereDate('send_transactions.created_at', Carbon::today())
            ->select(
                'send_transactions.id as transaction_id',
                'senders.name as buyer_name',
                'sender_details.account_image as buyer_image',
                'receivers.name as traveler_name',
                DB::raw('0 as total_price'),
                DB::raw("CONCAT('Kirim Barang (', send_transactions.delivery_method, ')') as service"),
                'payment_methods.type as payment_method',
                'send_transactions.payment_status as status',
                'send_transactions.created_at'
            );

        $todayTransactions = $buyTransactions
            ->unionAll($sendTransactions)
            ->orderBy('created_at', 'desc');

        $todayTransactions = DB::table(DB::raw("({$todayTransactions->toSql()}) as t"))
            ->mergeBindings($todayTransactions)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $todayTransactionsCount = $todayTransactions->count();

        return view('_finance.dashboard.index', compact(
            'income',
            'sendCount',
            'buyCount',
            'topUsers',            // top user sesuai range (withCount)
            'topUsersThisMonth',   // top user bulan ini (union buy+send)
            'latestUsers',
            'range',
            'todayIncome',
            'monthIncome',
            'chartLabels',
            'chartSendData',
            'chartBuyData',
            'weekTooltips',
            'todayTransactions',
            'todayTransactionsCount'
        ));
    }
    public function index2(Request $request)
    {
        $search = $request->input('search');

        // Buy Transactions dengan search
        $buyTransactions = BuyTransaction::with(['buyer', 'traveler', 'product', 'paymentMethod'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('buyer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('id', $search);
            })
            ->latest()
            ->paginate(20, ['*'], 'buy_page');

        // Send Transactions dengan search
        $sendTransactions = SendTransaction::with(['sender', 'receiver', 'product', 'paymentMethod'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('sender', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('id', $search);
            })
            ->latest()
            ->paginate(20, ['*'], 'send_page');

        return view('_finance.transaksi.index2', compact('buyTransactions', 'sendTransactions', 'search'));
    }
    public function destroyBuy($id)
    {
        $trx = BuyTransaction::findOrFail($id);

        // Hapus payment proof bila ada
        if ($trx->payment_proof && file_exists(storage_path('app/public/payment_proof/' . $trx->payment_proof))) {
            unlink(storage_path('app/public/payment_proof/' . $trx->payment_proof));
        }

        $trx->delete();

        return redirect()->back()->with('success', 'Buy Transaction berhasil dihapus.');
    }

    public function destroySend($id)
    {
        $trx = SendTransaction::findOrFail($id);

        if ($trx->payment_proof && file_exists(storage_path('app/public/payment_proof/' . $trx->payment_proof))) {
            unlink(storage_path('app/public/payment_proof/' . $trx->payment_proof));
        }

        $trx->delete();

        return redirect()->back()->with('success', 'Send Transaction berhasil dihapus.');
    }

    public function index3(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status'); // dari tombol radio

        // Ambil transaksi yang punya traveler
        $query = BuyTransaction::with(['traveler', 'paymentMethod'])
            ->whereNotNull('traveler_id');

        // Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari di traveler (username atau nama di user_details)
                $q->whereHas('traveler', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhereHas('detail', function ($d) use ($search) {
                            $d->where('name', 'like', "%{$search}%");
                        });
                })
                // Cari di ID transaksi
                ->orWhere('id', 'like', "%{$search}%")
                // Cari di payment method
                ->orWhereHas('paymentMethod', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                // Cari di bulan (angka 1-12)
                ->orWhereMonth('created_at', $search)
                // Cari di bulan (nama, misal: "September")
                ->orWhereRaw('MONTHNAME(created_at) LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter status dari tombol radio
        if ($statusFilter == 'approved') {
            $query->where('payment_status', 'approved');
        } elseif ($statusFilter == 'pending') {
            $query->where('payment_status', 'pending');
        }

        $commissions = $query->latest()->paginate(20);

        return view('_finance.traveler.index3', compact('commissions', 'statusFilter', 'search'));
    }
    public function validateCommission($id)
    {
        $trx = BuyTransaction::findOrFail($id);

        // Update status jadi approved
        $trx->payment_status = 'approved';
        $trx->save();

        return back()->with('success', 'Transaksi berhasil divalidasi.');
    }
    public function destroy($id)
    {
        $commission = BuyTransaction::findOrFail($id);

        // Hapus file payment proof jika ada
        if ($commission->payment_proof && \Storage::disk('public')->exists($commission->payment_proof)) {
            \Storage::disk('public')->delete($commission->payment_proof);
        }

        $commission->delete();

        return redirect()->back()->with('success', 'Komisi berhasil dihapus.');
    }

    public function index4(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        // --- Ambil & normalisasi transaksi kirim ---
        $sendTrx = SendTransaction::with(['sender.detail', 'paymentMethod'])
            ->select(
                'id',
                'sender_id',
                \DB::raw("'kirim' as trx_type"),
                'payment_status',
                'payment_method_id',
                'created_at'
            )
            ->get()
            ->map(function ($trx) {
                return (object)[
                    'id' => $trx->id,
                    'user_id' => $trx->sender_id,
                    'trx_type' => 'kirim',
                    'payment_status' => $trx->payment_status,
                    'payment_method' => $trx->paymentMethod,
                    'created_at' => $trx->created_at,
                    'user' => $trx->sender,
                ];
            });

        // --- Ambil & normalisasi transaksi beli ---
        $buyTrx = BuyTransaction::with(['buyer.detail', 'paymentMethod'])
            ->select(
                'id',
                'buyer_id',
                \DB::raw("'beli' as trx_type"),
                'payment_status',
                'payment_method_id',
                'created_at'
            )
            ->get()
            ->map(function ($trx) {
                return (object)[
                    'id' => $trx->id,
                    'user_id' => $trx->buyer_id,
                    'trx_type' => 'beli',
                    'payment_status' => $trx->payment_status,
                    'payment_method' => $trx->paymentMethod,
                    'created_at' => $trx->created_at,
                    'user' => $trx->buyer,
                ];
            });

        // --- Gabung transaksi ---
        $transactions = $sendTrx->merge($buyTrx);

        // --- Group berdasarkan user_id ---
        $grouped = $transactions->groupBy('user_id');

        // --- Mapping data user untuk view ---
        $users = $grouped->map(function ($trxGroup) {
            $trxLatest = $trxGroup->sortByDesc('created_at')->first();
            $user = $trxLatest->user;

            // fallback kalau user kosong
            if (!$user) {
                $user = \App\Models\User::with('detail')->find($trxLatest->user_id);
            }

            // nama dasar
            $nameBase = $user?->detail?->name
                ?? $user?->name
                ?? $user?->email
                ?? '-';

            $username = $user?->email ?? '-';
            $email = $user?->email ?? '-';

            // tipe transaksi gabungan
            $types = $trxGroup->pluck('trx_type')->unique()->values()->toArray();
            $typeLabel = count($types) > 1 ? 'beli dan kirim' : implode('', $types);

            $name = $nameBase . ' (' . $typeLabel . ')';

            // hitung total transaksi
            $totalTrx = $trxGroup->count();

            // payment methods
            $payments = $trxGroup->map(fn($t) => optional($t->payment_method)->name)
                ->filter()->unique()->implode(', ');

            // rekening
            $accounts = $trxGroup->map(fn($t) => optional($t->payment_method)->account_number)
                ->filter()->unique()->values();

            $accountDisplay = '-';
            if ($accounts->isNotEmpty()) {
                $joined = $accounts->implode(', ');
                if (strlen($joined) > 20) {
                    $accountDisplay = substr($joined, 0, 20) . '...' . ' (' . $accounts->count() . ')';
                } else {
                    $accountDisplay = $joined . ' (' . $accounts->count() . ')';
                }
            }

            // tanggal terakhir + jenis trx terakhir
            $latestDate = \Carbon\Carbon::parse($trxLatest->created_at)->format('d M Y');
            $latestWithType = $latestDate . ' (' . $trxLatest->trx_type . ')';

            return [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'types' => $typeLabel,
                'total_trx' => $totalTrx,
                'payments' => $payments,
                'accounts' => $accountDisplay,
                'accounts_raw' => $accounts->toArray(), // << untuk modal
                'payments_raw' => $trxGroup->map(fn($t) => optional($t->payment_method)->name)
                    ->filter()->unique()->values()->toArray(), // << untuk modal
                'latest_at' => $trxGroup->max('created_at'),
                'latest_with_type' => $latestWithType,
                'userdetails' => $user?->detail, // biar gampang ambil foto
            ];
        });

        // --- Filter search ---
        if ($search) {
            $s = strtolower(trim($search));

            $users = $users->filter(function ($u) use ($s) {
                $name = strtolower($u['name']);
                $email = strtolower($u['email']);
                $payments = strtolower($u['payments']);
                $types = strtolower($u['types']);

                return str_contains($name, $s)
                    || str_contains($email, $s)
                    || str_contains($payments, $s)
                    || str_contains($types, $s);
            });
        }

        // --- Sortir by latest transaction ---
        $users = $users->sortByDesc('latest_at');

        // --- Pagination manual ---
        $perPage = 20;
        $page = $request->input('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $users->forPage($page, $perPage),
            $users->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('_finance.penitip.index4', [
            'transactions' => $paginated,
            'page' => $page,
            'perPage' => $perPage,
        ]);
    }

    public function index5(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $refunds = \App\Models\Refund::with([
                'buyTransaction.buyer.detail',
                'buyTransaction.product',
                'buyTransaction.paymentMethod'
            ])
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('buyTransaction.buyer', function ($buyer) use ($search) {
                            $buyer->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('buyTransaction.product', function ($product) use ($search) {
                            $product->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('buyTransaction.paymentMethod', function ($pm) use ($search) {
                            $pm->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('_finance.refund.index5', compact('refunds', 'search', 'status'));
    }
    public function updateRefund(Request $request, $id)
    {
        $refund = \App\Models\Refund::findOrFail($id);
        $refund->status = $request->input('status');
        $refund->save();

        return back()->with('success', 'Refund updated successfully');
    }

    public function index6()
    {
        $user = Auth::user();
        $user->loadMissing('detail');

        return view('_finance.pengaturan.index6', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->loadMissing('detail');

        // Validasi termasuk password
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'current_password' => 'required',
        ]);

        // Cek password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password salah.']);
        }

        // update ke tabel users
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]
        );

        return redirect()->route('finance.index6')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // cek current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            // Hapus foto lama kalau ada
            if ($user->detail->account_image && Storage::exists('public/'.$user->detail->account_image)) {
                Storage::delete('public/'.$user->detail->account_image);
            }

            // Ambil extension file
            $ext = $request->file('profile_image')->getClientOriginalExtension();

            // Format nama file: id_role.ext
            $filename = $user->id . '_' . $user->role . '.' . $ext;

            // Simpan di folder profile_images
            $path = $request->file('profile_image')->storeAs('profile_images', $filename, 'public');

            // Update database
            $user->detail->update([
                'account_image' => $path
            ]);
        }

        return back()->with('success', 'Profile image updated successfully.');
    }
    public function updateIdCard(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'id_card_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'required',
        ]);

        // Cek password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password salah.']);
        }

        // Simpan file jika ada
        if ($request->hasFile('id_card_image')) {
            $file = $request->file('id_card_image');
            $filename = $user->id . '_' . $user->role . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('idcard_images', $filename, 'public');

            $user->detail->id_card_image = $path;
            $user->detail->save();
        }

        return back()->with('success', 'ID Card updated');
    }

    public function updatePassport(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'pasport_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'required',
        ]);

        // Cek password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password salah.']);
        }

        // Simpan file jika ada
        if ($request->hasFile('pasport_image')) {
            $file = $request->file('pasport_image');
            $filename = $user->id . '_' . $user->role . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pasport_images', $filename, 'public');

            $user->detail->pasport_image = $path;
            $user->detail->save();
        }

        return back()->with('success', 'Passport updated');
    }

    public function index7()  { return view('_finance.dashboard.index7'); }
    public function index8()  { return view('_finance.dashboard.index8'); }
    public function index9()  { return view('_finance.dashboard.index9'); }
    public function index10() { return view('_finance.dashboard.index10'); }
}
