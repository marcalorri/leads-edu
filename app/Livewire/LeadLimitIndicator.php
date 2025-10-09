<?php

namespace App\Livewire;

use App\Services\LeadLimitService;
use Filament\Facades\Filament;
use Livewire\Component;

class LeadLimitIndicator extends Component
{
    public int $currentCount = 0;
    public int $maxLeads = 0;
    public int $remaining = 0;
    public float $percentage = 0;
    public bool $isUnlimited = false;
    public bool $showWarning = false;
    public bool $isCritical = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $tenant = Filament::getTenant();
        
        if (!$tenant) {
            return;
        }

        $leadLimitService = app(LeadLimitService::class);

        $this->currentCount = $leadLimitService->getCurrentLeadsCount($tenant);
        $this->maxLeads = $leadLimitService->getMaxLeads($tenant);
        $this->remaining = $leadLimitService->getRemainingLeads($tenant);
        $this->percentage = $leadLimitService->getUsagePercentage($tenant);
        $this->isUnlimited = $this->maxLeads === PHP_INT_MAX;
        $this->showWarning = $leadLimitService->shouldShowWarning($tenant);
        $this->isCritical = $leadLimitService->isCritical($tenant);
    }

    public function render()
    {
        return view('livewire.lead-limit-indicator');
    }
}
