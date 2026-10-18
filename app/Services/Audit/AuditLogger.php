<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public function log(string $action, Model $auditable, ?string $summary = null, ?array $before = null, ?array $after = null): AuditLog
    {
        return AuditLog::query()->create([
            'user_id' => Auth::id(),
            'auditable_type' => $auditable->getMorphClass(),
            'auditable_id' => $auditable->getKey(),
            'action' => $action,
            'summary' => $summary,
            'before' => $before,
            'after' => $after,
            'ip_address' => Request::ip(),
            'user_agent' => mb_substr((string) Request::userAgent(), 0, 255),
        ]);
    }
}
