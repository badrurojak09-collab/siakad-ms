<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class AdmissionBill extends Model { use SoftDeletes,BelongsToTenant; protected $guarded=[]; protected $casts=['issued_at'=>'datetime','due_date'=>'date','amount'=>'decimal:2','paid_amount'=>'decimal:2','metadata'=>'array']; public function applicant(){return $this->belongsTo(Applicant::class);} public function feeType(){return $this->belongsTo(FeeType::class);} public function payments(){return $this->hasMany(AdmissionPayment::class);} public function getOutstandingAmountAttribute(){return max(0,(float)$this->amount-(float)$this->paid_amount);} }
