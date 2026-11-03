<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Division;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Policies\ApplicationPolicy;
use App\Policies\DivisionPolicy;
use App\Policies\InterviewPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\PositionPolicy;
use App\Policies\RequisitionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Division::class, DivisionPolicy::class);
        Gate::policy(Position::class, PositionPolicy::class);
        Gate::policy(Requisition::class, RequisitionPolicy::class);
        Gate::policy(JobPosting::class, JobPostingPolicy::class);
        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Interview::class, InterviewPolicy::class);
    }
}
