<?php
 
namespace App\Models;
 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Permissions\HasPermissionsTrait;
 
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasPermissionsTrait;
    protected $primaryKey   = 'id';
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','firstname','lastname', 'email', 'password','telephone','fonction','status'
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
 
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function secteur() {
      return $this->belongsToMany(SousSecteur::class,'users_sous_secteurs');          
    }

    public function structures() {

      return $this->belongsToMany(Structure::class,'users_structures');
            
    }

    public function roles() {

      return $this->belongsToMany(Role::class,'role_user');
            
    }

    public function permissions() {

      return $this->belongsToMany(Permision::class,'users_permissions');
            
    }

    public function users()
    {
        return $this
            ->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function authorizeRoles($roles)
    {
      if ($this->hasAnyRole($roles)) {
        return true;
      }
      abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
      if (is_array($roles)) {
        foreach ($roles as $role) {
          if ($this->hasRole($role)) {
            return true;
          }
        }
      } else {
        if ($this->hasRole($roles)) {
          return true;
        }
      }
      return false;
    }

    public function hasRole($role)
    {
      if ($this->roles()->where('name', $role)->first()) {
        return true;
      }
      return false;
    }
    public function hasPermission($permission_name)
    {
      if (!empty($this->roles)) {
        foreach ($this->roles as $role) {
          if ($role->permissions()->where('name', $permission_name)->first()) {
            return true;
          }
        }
      }
      return false;
    }

    public function getStructure()
    {
      return $this->structures();
    }
}