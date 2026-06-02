<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    /**
     * Create an audit log entry.
     *
     * Required:
     * - tenant_id
     * - user_id
     * - log_type
     * - entity_type
     * - entity_id
     * - action
     *
     * Optional:
     * - clinic_id
     * - original_value
     * - new_value
     * - ip_address
     * - device_info
     *
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        AuditLog::create([
            'tenant_id'     => $data['tenant_id'],
            'user_id'       => $data['user_id'],
            'clinic_id'     => $data['clinic_id'] ?? null,
            'log_type'      => $data['log_type'],
            'entity_type'   => $data['entity_type'],
            'entity_id'     => $data['entity_id'],
            'action'        => $data['action'],
            'original_value'=> $data['original_value'] ?? null,
            'new_value'     => $data['new_value'] ?? null,
            'ip_address'    => $data['ip_address'] ?? null,
            'device_info'   => $data['device_info'] ?? null,
            'created_at'    => now(),
        ]);
    }
}