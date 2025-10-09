<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Lead;

class LeadLimitService
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    /**
     * Obtener el límite máximo de leads para un tenant
     */
    public function getMaxLeads(Tenant $tenant): int
    {
        $metadata = $this->subscriptionService
            ->getTenantSubscriptionProductMetadata($tenant);
        
        // Si no hay metadata o no hay max_leads, retornar límite por defecto
        if (empty($metadata) || !isset($metadata['max_leads'])) {
            return 50; // Límite por defecto
        }
        
        $maxLeads = (int) $metadata['max_leads'];
        
        // -1 significa ilimitado
        return $maxLeads === -1 ? PHP_INT_MAX : $maxLeads;
    }

    /**
     * Obtener el número actual de leads del tenant
     */
    public function getCurrentLeadsCount(Tenant $tenant): int
    {
        return Lead::where('tenant_id', $tenant->id)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Verificar si el tenant puede crear más leads
     */
    public function canCreateLead(Tenant $tenant): bool
    {
        $maxLeads = $this->getMaxLeads($tenant);
        $currentCount = $this->getCurrentLeadsCount($tenant);
        
        return $currentCount < $maxLeads;
    }

    /**
     * Obtener leads restantes
     */
    public function getRemainingLeads(Tenant $tenant): int
    {
        $maxLeads = $this->getMaxLeads($tenant);
        
        if ($maxLeads === PHP_INT_MAX) {
            return -1; // Ilimitado
        }
        
        $currentCount = $this->getCurrentLeadsCount($tenant);
        return max(0, $maxLeads - $currentCount);
    }

    /**
     * Obtener porcentaje de uso
     */
    public function getUsagePercentage(Tenant $tenant): float
    {
        $maxLeads = $this->getMaxLeads($tenant);
        
        if ($maxLeads === PHP_INT_MAX) {
            return 0; // Ilimitado
        }
        
        $currentCount = $this->getCurrentLeadsCount($tenant);
        
        if ($maxLeads === 0) {
            return 0;
        }
        
        return ($currentCount / $maxLeads) * 100;
    }

    /**
     * Verificar si debe mostrar advertencia
     */
    public function shouldShowWarning(Tenant $tenant): bool
    {
        $percentage = $this->getUsagePercentage($tenant);
        $remaining = $this->getRemainingLeads($tenant);
        
        return $percentage >= 75 && $remaining !== -1;
    }

    /**
     * Verificar si es crítico (>90%)
     */
    public function isCritical(Tenant $tenant): bool
    {
        return $this->getUsagePercentage($tenant) >= 90;
    }
}
