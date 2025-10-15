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
     * Get the maximum lead limit for a tenant
     */
    public function getMaxLeads(Tenant $tenant): int
    {
        $metadata = $this->subscriptionService
            ->getTenantSubscriptionProductMetadata($tenant);
        
        // If there's no metadata or no max_leads, return default limit
        if (empty($metadata) || !isset($metadata['max_leads'])) {
            return 50; // Default limit
        }
        
        $maxLeads = (int) $metadata['max_leads'];
        
        // -1 means unlimited
        return $maxLeads === -1 ? PHP_INT_MAX : $maxLeads;
    }

    /**
     * Get the current number of leads for the tenant
     */
    public function getCurrentLeadsCount(Tenant $tenant): int
    {
        return Lead::where('tenant_id', $tenant->id)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check if the tenant can create more leads
     */
    public function canCreateLead(Tenant $tenant): bool
    {
        $maxLeads = $this->getMaxLeads($tenant);
        $currentCount = $this->getCurrentLeadsCount($tenant);
        
        return $currentCount < $maxLeads;
    }

    /**
     * Get remaining leads
     */
    public function getRemainingLeads(Tenant $tenant): int
    {
        $maxLeads = $this->getMaxLeads($tenant);
        
        if ($maxLeads === PHP_INT_MAX) {
            return -1; // Unlimited
        }
        
        $currentCount = $this->getCurrentLeadsCount($tenant);
        return max(0, $maxLeads - $currentCount);
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentage(Tenant $tenant): float
    {
        $maxLeads = $this->getMaxLeads($tenant);
        
        if ($maxLeads === PHP_INT_MAX) {
            return 0; // Unlimited
        }
        
        $currentCount = $this->getCurrentLeadsCount($tenant);
        
        if ($maxLeads === 0) {
            return 0;
        }
        
        return ($currentCount / $maxLeads) * 100;
    }

    /**
     * Check if warning should be shown
     */
    public function shouldShowWarning(Tenant $tenant): bool
    {
        $percentage = $this->getUsagePercentage($tenant);
        $remaining = $this->getRemainingLeads($tenant);
        
        return $percentage >= 75 && $remaining !== -1;
    }

    /**
     * Check if it's critical (>90%)
     */
    public function isCritical(Tenant $tenant): bool
    {
        return $this->getUsagePercentage($tenant) >= 90;
    }
}
