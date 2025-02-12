<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use HasUuids;

    protected $primaryKey = 'id';

    public $increamenting = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'gen',
        'departement',
        'comdev',
        'major',
        'angkatan',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rsvp()
    {
        return $this->hasMany(Rsvp::class,'user_id');
    }

    public function points()
    {
        return $this->hasMany(UserPoint::class, 'user_id');
    }

    public function pointHistories()
    {
        return $this->hasMany(UserPointHistory::class);
    }

    public function rsvpCountForCurrentPeriod()
    {
        $currentPeriod = Event::getCurrentPeriod();

        return $this->rsvp()
                    ->whereHas('event', function ($query) use ($currentPeriod) {
                        $query->where('priode', $currentPeriod);
                    })
                    ->count();
    }

    public function totalPointsForCurrentPeriod()
    {
        $currentPeriod = Event::getCurrentPeriod();

        return $this->hasOne(UserPoint::class, 'user_id')
                    ->where('periode', $currentPeriod)
                    ->value('point') ?? 0;
    }


    public function approvedRsvps()
    {
        return $this->hasMany(Rsvp::class)->where('status', 'APPROVED');
    }

}
