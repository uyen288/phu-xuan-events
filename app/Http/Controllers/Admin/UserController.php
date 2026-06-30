<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * M1.4: Admin quản lý user (CRUD)
 */
class UserController extends Controller
{
    /**
     * Danh sách tất cả users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tạo user mới
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu user mới vào DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin', 'organizer', 'student'])],
        ], [
            'name.required'      => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required'      => 'Vui lòng chọn vai trò.',
            'role.in'            => 'Vai trò không hợp lệ.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo tài khoản thành công.');
    }

    /**
     * Form chỉnh sửa user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in(['admin', 'organizer', 'student'])],
        ], [
            'name.required'  => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique'   => 'Email này đã được sử dụng.',
            'role.required'  => 'Vui lòng chọn vai trò.',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        // Đổi mật khẩu nếu Admin nhập
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['min:8', 'confirmed'],
            ], [
                'password.min'       => 'Mật khẩu tối thiểu 8 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật tài khoản thành công.');
    }

    /**
     * Xóa user
     */
    public function destroy(User $user)
    {
        // Không cho phép Admin tự xóa chính mình
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa tài khoản thành công.');
    }
}
