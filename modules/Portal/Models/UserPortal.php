<?php

namespace Modules\Portal\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Searchable\Searchable;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Auth\Notifications\ForgotPasswordNotification;
use Modules\Account\Database\Factories\UserFactory;
use App\Models\Traits\HasRole;

class UserPortal extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRole, Notifiable, Metable, Searchable, Restorable, Userstamps;

    /**
     * Define the meta table
     */
    public $metaTable = 'user_metas';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'username', 'email_address', 'email_verified_at', 'password', 'embark_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'email_verified_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'display_name'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name', 'username'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email_address;
    }

    /**
     * Specifies the user's FCM tokens
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return $this->getDeviceTokens();
    }

    /**
     * Get the e-mail address where password reset links are sent.
     */
    public function getEmailForPasswordReset()
    {
        return $this->email_address;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ForgotPasswordNotification($token));
    }

    /**
     * Find the user instance for the given value.
     */
    public function findForPassport($value)
    {
        return $this->where((filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email_address' : 'username'), $value)->first();
    }

    /**
     * Interact with the user's password.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get display name attributes.
     */
    public function getDisplayNameAttribute()
    {
        return $this->is(Auth::user()) ? 'Your' : $this->name;
    }

    /**
     * Get profile avatar path attributes.
     */
    public function getProfileAvatarPathAttribute()
    {
        return $this->relationLoaded('meta') && Storage::exists($this->getMeta('profile_avatar', 0))
            ? Storage::url($this->getMeta('profile_avatar'))
            : asset('/img/default-avatar.svg');
    }

    /**
     * get all FCM token from current user
     */
    public function getDeviceTokens()
    {
        return array_filter([$this->pushtoken->pluck('token')]);
    }

    /**
     * get all FCM token from current user
     */
    public function pushtoken()
    {
        return $this->hasMany(UserToken::class, 'user_id');
    }

    /**
     * This has many logs.
     */
    // public function employee()
    // {
    //     return $this->hasOne(Employee::class, 'user_id');
    // }

    public function role(){
        return $this->hasOne(UserRole::class, 'user_id');
    }

    /**
     * This has many logs.
     */
    public function logs()
    {
        return $this->hasMany(UserLog::class, 'user_id');
    }

    public function memberdonator()
    {
        return $this->hasOne(MemberDonator::class, 'user_id');
    }

    public function membervolunteer()
    {
        return $this->hasOne(MemberVolunteer::class, 'user_id');
    }

    public function memberpartnership(){
        return $this->hasOne(Memberpartnership::class, 'user_id');
    }

    /**
     * Create log.
     */
    public function log($message, $modelable_type = null, $modelable_id = null)
    {
        $ip = getClientIp();
        $user_agent = getenv('HTTP_USER_AGENT');

        return $this->logs()->create(compact('message', 'modelable_type', 'modelable_id', 'ip', 'user_agent'));
    }
}
