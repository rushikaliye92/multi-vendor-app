<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;
    protected $fillable = ['user_id','vendor_id','total'];
    
    public function items() { 
        return $this->hasMany(OrderItem::class); 
    }
    public function vendor() { 
        return $this->belongsTo(Vendor::class); 
    }
    public function user() { 
        return $this->belongsTo(User::class); 
    }
    public function payment() { 
        return $this->hasOne(Payment::class); 
    }
}
