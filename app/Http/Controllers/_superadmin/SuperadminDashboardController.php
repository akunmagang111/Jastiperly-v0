<?php

namespace App\Http\Controllers\_superadmin;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\BuyTransaction;
use App\Models\SendTransaction;
use App\Models\Product;
use App\Models\Refund;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SuperadminDashboardController extends Controller
{
    // ================= Dashboard =================
public function index()
{
    // === Users summary ===
    $totalUsers     = User::count();
    $newUsersLast30 = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

    // === Transactions summary ===
    $totalTransactions  = BuyTransaction::sum('total_price');
    $transactionsLast30 = BuyTransaction::where('created_at', '>=', Carbon::now()->subDays(30))
                            ->sum('total_price');

    $approvedTransactions  = BuyTransaction::where('payment_status', 'approved')->count();
    $pendingTransactions   = BuyTransaction::where('payment_status', 'pending')->count();
    $canceledTransactions  = BuyTransaction::where('payment_status', 'declined')->count();

    $deliveryTransactions = SendTransaction::count();

    // === Penitip (User role customer) per bulan ===
    $rawCustomers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('role', 'customer')
        ->where('created_at', '>=', Carbon::now()->subYear())
        ->groupBy('month')
        ->get();

    // === Traveler (User role traveler) per bulan ===
    $rawTravelers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('role', 'traveler')
        ->where('created_at', '>=', Carbon::now()->subYear())
        ->groupBy('month')
        ->get();

    // === Normalisasi 12 bulan terakhir ===
    $usersByMonth = collect(range(1, 12))->mapWithKeys(function ($month) use ($rawCustomers, $rawTravelers) {
        return [
            $month => [
                'penitip'  => $rawCustomers->firstWhere('month', $month)->total ?? 0,
                'traveler' => $rawTravelers->firstWhere('month', $month)->total ?? 0,
            ]
        ];
    });

    // === Latest transactions (5 terbaru) ===
    $latestTransactions = BuyTransaction::with(['buyer', 'traveler', 'paymentMethod'])
                            ->latest()
                            ->take(5)
                            ->get();

    return view('_superadmin.dashboard.index', compact(
        'totalUsers',
        'newUsersLast30',
        'totalTransactions',
        'transactionsLast30',
        'approvedTransactions',
        'pendingTransactions',
        'canceledTransactions',
        'deliveryTransactions',
        'usersByMonth',
        'latestTransactions'
    ));
}

    // ================= Manajemen Pengguna =================
public function index2(Request $request)
{
    $role   = $request->input('role', 'traveler');
    $search = $request->input('search');

    $query = User::query()
        ->when($role, function ($q) use ($role) {
            $q->where('role', $role);
        })
        ->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->latest();

    $users = $query->paginate(10)->appends([
        'role'   => $role,
        'search' => $search,
    ]);

    return view('_superadmin.users.index', compact('users', 'role', 'search'));
}

/**
 * Export data user
 */
public function usersExport(Request $request)
{
    $role = $request->get('role', 'traveler');
    $users = User::where('role', $role)->get();

    // Contoh sederhana (export ke CSV)
    $filename = "users-{$role}.csv";
    $handle = fopen($filename, 'w+');
    fputcsv($handle, ['ID', 'Nama', 'Email', 'Role']);

    foreach ($users as $user) {
        fputcsv($handle, [$user->id, $user->name, $user->email, $user->role]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}

/**
 * Tampilkan detail user
 */
public function usersShow(User $user)
{
    return view('_superadmin.users.show', compact('user'));
}

/**
 * Form edit user
 */
public function usersEdit(User $user)
{
    return view('_superadmin.users.edit', compact('user'));
}

/**
 * Update data user
 */
public function usersUpdate(Request $request, User $user)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role'  => 'required|in:traveler,customer,admin,finance',
    ]);

    $user->update([
        'name'  => $request->name,
        'email' => $request->email,
        'role'  => $request->role,
    ]);

    return redirect()->route('superadmin.users.index', ['role' => $user->role])
        ->with('success', 'Data pengguna berhasil diperbarui!');
}

public function usersDestroy(User $user)
{
    $role = $user->role;
    $user->delete();

    return redirect()->route('superadmin.users.index', ['role' => $role])
        ->with('success', 'Pengguna berhasil dihapus!');
}



    // ================= Manajemen Produk =================
    public function index3(Request $request)
{
    $role     = $request->get('role');
    $approval = $request->get('approval');
    $search   = $request->get('search');

    $query = Product::with('submiter','category');

    if ($role) {
        $query->whereHas('submiter', function($q) use ($role) {
            $q->where('role', $role);
        });
    }

    if ($approval) {
        $query->where('approval', $approval);
    }

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    $products = $query->latest()->paginate(10)->appends($request->all());

    $roleCounts = [
        'traveler' => Product::whereHas('submiter', fn($q) => $q->where('role','traveler'))->count(),
        'customer' => Product::whereHas('submiter', fn($q) => $q->where('role','customer'))->count(),
    ];

    return view('_superadmin.products.index', compact('products','role','approval','search','roleCounts'));
}


    public function create()
    {
        $submiters  = User::whereIn('role',['traveler','customer'])->get();
        $categories = ProductCategory::all();
        return view('_superadmin.products.create', compact('submiters','categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'               => 'required|string|max:255',
        'description'        => 'nullable|string',
        'price'              => 'required|numeric|min:0',
        'category_id'        => 'required|exists:product_categories,id',
        'role'               => 'required|in:traveler,customer',
        'submiter_id'        => 'nullable|exists:users,id',
        'new_submitter_name' => 'nullable|string|max:255',
        'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        // Tentukan submitter
        if ($request->filled('new_submitter_name')) {
            $submitter = User::create([
                'name'     => $request->new_submitter_name,
                'role'     => $request->role,
                'email'    => 'user' . Str::random(8) . '@example.com',
                'password' => Hash::make('password'),
            ]);
            $submiterId = $submitter->id;
        } else {
            $submiterId = $request->submiter_id ?: null;
        }

        // Upload gambar jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        // Simpan produk baru
        Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'category_id' => $request->category_id,
            'role'        => $request->role,
            'submiter_id' => $submiterId,
            'approval'    => 'pending',
            'status'      => 'active',
            'image'       => $imagePath,
        ]);

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function showProduct($id)
    {
        $product = Product::with('submiter','category')->findOrFail($id);
        return view('_superadmin.products.show', compact('product'));
    }

    public function editProduct($id)
    {
        $product    = Product::findOrFail($id);
        $submiters  = User::whereIn('role',['traveler','customer'])->get();
        $categories = ProductCategory::all();
        return view('_superadmin.products.edit', compact('product','submiters','categories'));
    }

   public function updateProduct(Request $request, $id)
{
    $request->validate([
        'name'               => 'required|string|max:255',
        'description'        => 'nullable|string',
        'price'              => 'required|numeric|min:0',
        'category_id'        => 'required|exists:product_categories,id',
        'submiter_id'        => 'nullable|exists:users,id',
        'new_submitter_name' => 'nullable|string|max:255',
        'approval'           => 'nullable|in:pending,approved,declined',
        'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'role'               => 'nullable|in:traveler,customer', // kalau mau set role untuk submitter baru
    ]);

    $product = Product::findOrFail($id);

    // Jika ada nama submitter baru, buat user baru dulu
    if ($request->filled('new_submitter_name')) {
        $user = User::create([
            'name'     => $request->new_submitter_name,
            'role'     => $request->role ?? 'customer',
            'email'    => 'user' . Str::random(8) . '@example.com',
            'password' => Hash::make('password123'),
        ]);
        $product->submiter_id = $user->id;
    } else {
        $product->submiter_id = $request->submiter_id;
    }

    // Upload gambar jika ada
    if ($request->hasFile('image')) {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->image = $request->file('image')->store('products','public');
    }

    // Update field lainnya
    $product->name        = $request->name;
    $product->description = $request->description;
    $product->price       = $request->price;
    $product->category_id = $request->category_id;
    $product->approval    = $request->approval ?? 'pending';
    $product->save();

    return redirect()->route('superadmin.products.index')
                     ->with('success','Produk berhasil diperbarui.');
}



    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->route('superadmin.products.index')
            ->with('success','Produk berhasil dihapus!');
    }

    public function validateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate(['approval'=>'required|in:approved,declined']);
        $product->approval = $request->approval;
        $product->save();

        return redirect()->route('superadmin.products.index')
            ->with('success', "Produk berhasil {$request->approval}!");
    }

    public function exportProducts()
    {
        $fileName = "products_export_".now()->format('Y-m-d_H-i-s').".csv";
        $products = Product::with('submiter','category')->get();

        return response()->streamDownload(function() use($products){
            $handle = fopen('php://output','w');
            fputcsv($handle,['ID','Nama Produk','Deskripsi','Harga','Submitter','Role','Kategori','Status','Approval']);
            foreach($products as $product){
                fputcsv($handle,[
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->price,
                    optional($product->submiter)->name,
                    $product->role,
                    optional($product->category)->name,
                    $product->status,
                    $product->approval,
                ]);
            }
            fclose($handle);
        }, $fileName);
    }

    // ================= Transaksi =================
    public function index4(Request $request)
    {
        $countBeli = BuyTransaction::count();
        $countKirim = SendTransaction::count();
        $transactions = BuyTransaction::with(['buyer', 'paymentMethod'])->latest()->paginate(10);

        return view('_superadmin.transactions.index', compact('transactions', 'countBeli', 'countKirim'));
    }

    public function showTransaction($id)
    {
        $transaction = BuyTransaction::with(['buyer', 'paymentMethod'])->findOrFail($id);
        return view('_superadmin.transactions.show', compact('transaction'));
    }

    public function editTransaction($id)
    {
        $transaction = BuyTransaction::with(['buyer', 'paymentMethod'])->findOrFail($id);
        return view('_superadmin.transactions.edit', compact('transaction'));
    }

    public function updateTransaction(Request $request, $id)
    {
        $transaction = BuyTransaction::findOrFail($id);
        $allowedStatus = ['pending','approved','declined'];

        if (!in_array($request->payment_status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $transaction->update([
            'payment_status' => $request->payment_status,
            'total_price'    => $request->total_price,
        ]);

        return redirect()->route('superadmin.transactions.index')
            ->with('success','Transaksi berhasil diperbarui.');
    }

    public function destroyTransaction($id)
    {
        $transaction = BuyTransaction::findOrFail($id);
        $transaction->delete();
        return redirect()->back()->with('success','Transaksi berhasil dihapus');
    }

    public function exportTransactions(Request $request)
{
    $transactions = BuyTransaction::with('buyer'); // cukup ini, jangan ->query()

    if ($request->filled('search')) {
        $transactions->whereHas('buyer', fn($q) =>
            $q->where('name','like',"%{$request->search}%")
        );
    }

    if ($request->filled('payment_status')) {
        $transactions->where('payment_status', $request->payment_status);
    }

    $data = $transactions->get();

    $filename = 'transactions_' . date('Ymd_His') . '.csv';
    $handle = fopen($filename,'w+');
    fputcsv($handle,['ID','Buyer','Total Price','Payment Status','Created At']);

    foreach ($data as $row) {
        fputcsv($handle,[
            $row->id,
            $row->buyer?->name ?? '-',
            $row->total_price,
            $row->payment_status,
            $row->created_at,
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
    }


   // ================= Refund =================
public function refundsIndex(Request $request)
{
    $refunds = Refund::with([
            'buyTransaction.buyer',        // Data pembeli
            'buyTransaction.traveler',     // Data traveler
            'buyTransaction.product',      // Data produk
            'buyTransaction.paymentMethod' // Metode pembayaran
        ])
        // Filter berdasarkan status (jika ada)
        ->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        })
        // Filter pencarian (nama buyer, traveler, produk)
        ->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;

            $q->where(function ($sub) use ($search) {
                $sub->whereHas('buyTransaction.buyer', function ($buyer) use ($search) {
                        $buyer->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buyTransaction.traveler', function ($traveler) use ($search) {
                        $traveler->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buyTransaction.product', function ($product) use ($search) {
                        $product->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('_superadmin.refunds.index', [
        'refunds' => $refunds,
        'status'  => $request->status ?? '',
        'search'  => $request->search ?? '',
    ]);
}
// Detail Refund
public function refundsShow($id)
{
    $refund = Refund::with([
        'buyTransaction.buyer',
        'buyTransaction.traveler',
        'buyTransaction.product',
        'buyTransaction.paymentMethod'
    ])->findOrFail($id);

    return view('_superadmin.refunds.show', compact('refund'));
}

// Form Edit Refund
public function refundsEdit($id)
{
    $refund = Refund::with('buyTransaction')->findOrFail($id);

    return view('_superadmin.refunds.edit', compact('refund'));
}

// Update Refund
public function refundsUpdate(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,declined',
        'reason' => 'nullable|string|max:255',
    ]);

    $refund = Refund::findOrFail($id);
    $refund->update($request->only(['status', 'reason']));

    return redirect()->route('superadmin.refunds.index')
                     ->with('success', 'Refund berhasil diperbarui!');
}

// Hapus Refund
public function refundsDestroy($id)
{
    $refund = Refund::findOrFail($id);
    $refund->delete();

    return redirect()->route('superadmin.refunds.index')
                     ->with('success', 'Refund berhasil dihapus!');
}

// Refund Export 
public function exportRefunds(Request $request)
{
    $refunds = Refund::with([
        'buyTransaction.buyer',
        'buyTransaction.traveler',
        'buyTransaction.product',
        'buyTransaction.paymentMethod'
    ]);

    // Filter status
    if ($request->filled('status')) {
        $refunds->where('status', $request->status);
    }

    // Filter search (buyer / traveler / produk)
    if ($request->filled('search')) {
        $search = $request->search;
        $refunds->where(function ($q) use ($search) {
            $q->whereHas('buyTransaction.buyer', fn($buyer) => $buyer->where('name', 'like', "%$search%"))
              ->orWhereHas('buyTransaction.traveler', fn($traveler) => $traveler->where('name', 'like', "%$search%"))
              ->orWhereHas('buyTransaction.product', fn($product) => $product->where('name', 'like', "%$search%"));
        });
    }

    $data = $refunds->get();

    // Buat file CSV
    $filename = 'refunds_' . date('Ymd_His') . '.csv';
    $handle = fopen($filename, 'w+');
    fputcsv($handle, ['ID Refund', 'Pembeli', 'Traveler', 'Produk', 'Total Harga', 'Status', 'Tanggal Refund']);

    foreach ($data as $refund) {
        fputcsv($handle, [
            $refund->id,
            $refund->buyTransaction?->buyer?->name ?? '-',
            $refund->buyTransaction?->traveler?->name ?? '-',
            $refund->buyTransaction?->product?->name ?? '-',
            $refund->buyTransaction?->total_price ?? 0,
            ucfirst($refund->status),
            $refund->created_at?->format('d-m-Y H:i:s'),
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}


// ================= Pengaturan =================
public function index6()
{
    $user = Auth::user();
    return view('_superadmin.settings.index', compact('user'));
}

public function updateSettings(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'username'      => 'required|string|max:255',
        'name'          => 'required|string|max:255',
        'email'         => 'required|email',
        'telepon'       => 'nullable|string|max:20',
        'alamat'        => 'nullable|string|max:255',
        'jenis_kelamin' => 'nullable|in:L,P',
    ]);

    $user->update($request->only(['username','name','email','telepon','alamat','jenis_kelamin']));
    return back()->with('success','Profil berhasil diperbarui!');
}

public function updatePreference(Request $request)
{
    $user = Auth::user();
    $request->validate(['language'=>'required|string|in:id,en']);
    $user->language = $request->language;
    $user->save();
    app()->setLocale($request->language);

    return back()->with('success','Preferensi berhasil diperbarui!');
}

public function updatePassword(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'old_password'=>'required',
        'new_password'=>'required|min:6|confirmed',
    ]);

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->withErrors(['old_password'=>'Password lama salah']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();
    return back()->with('success','Password berhasil diperbarui!');
}

// ================= Profile =================
public function profileIndex()
{
    $user = Auth::user()->load('detail'); // ikut load detail biar gampang dipanggil di view
    return view('_superadmin.profile.index', compact('user'));
}

public function profileUpdate(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email,' . $user->id,
        'phone'  => 'nullable|string|max:20',
        'bio'    => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // --- update tabel users
    $user->update([
        'name'  => $request->name,
        'email' => $request->email,
    ]);

    // --- update / insert tabel user_details
    $detail = $user->detail ?: $user->detail()->create();

    $dataDetail = [
        'phone' => $request->phone,
        'address' => $request->bio, // kalau bio mau dipakai sebagai address, bisa diganti kalau ada field lain
    ];

    // simpan avatar kalau ada
    if ($request->hasFile('avatar')) {
        // ambil user id & role
        $filename = $user->id . '_' . $user->role . '.' . $request->file('avatar')->getClientOriginalExtension();

        // simpan di folder profile_images dengan nama custom
        $path = $request->file('avatar')->storeAs('profile_images', $filename, 'public');

        $dataDetail['account_image'] = $path;
    }

    $detail->update($dataDetail);

    return back()->with('success', 'Profil berhasil diperbarui!');
}

public function profilePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|confirmed|min:6',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->withErrors(['old_password' => 'Password lama salah']);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password berhasil diperbarui!');
}



// ================= Halaman Lain =================
public function index7() { return view('_superadmin.dashboard.index7'); }
public function index8() { return view('_superadmin.dashboard.index8'); }
public function index9() { return view('_superadmin.dashboard.index9'); }
public function index10(){ return view('_superadmin.dashboard.index10'); }
}
