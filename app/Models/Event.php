<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'capacity',
        'status',
        'user_id',
        'category_id',
        'banner', // Thêm trường này để lưu ảnh banner (M2.4 nâng cao)
    ];

    /**
     * Đồng bộ kiểu dữ liệu khi lấy từ DB ra
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Mối quan hệ: Một sự kiện thuộc về một người tổ chức (User)
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias của organizer() - Để tương thích hoàn toàn với EventController::with(['user'])
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mối quan hệ: Sự kiện thuộc về một danh mục
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Mối quan hệ: Một sự kiện có nhiều đơn đăng ký tham gia
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Mối quan hệ nhiều-nhiều: Lấy ra danh sách Sinh viên đã đăng ký sự kiện này thông qua bảng trung gian
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('status', 'note')
            ->withTimestamps();
    }

    /**
     * Mối quan hệ nhiều-nhiều: Sự kiện có nhiều thẻ từ khóa (Tags)
     */
    public function tags()
    {
        // Đồng bộ chuẩn đặt tên bảng pivot là 'event_tag' như trong Prompt 1.1 đề cương
        return $this->belongsToMany(Tag::class, 'event_tags');
    }

    /**
     * Local Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now());
    }
}