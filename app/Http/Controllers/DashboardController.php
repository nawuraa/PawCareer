<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\Application;
use App\Models\ApplicationNotification;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Requisition;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function manager()
    {
        $user = Auth::user();
        $divisionId = $user->division_id;

        $allRequisitions = Requisition::with(['position', 'requester'])
            ->whereHas('position', fn ($q) => $q->where('division_id', $divisionId))
            ->latest()
            ->get();

        $metrics = [
            'pending'       => $allRequisitions->whereIn('status', ['Diajukan', 'submitted'])->count(),
            'approved'      => $allRequisitions->whereIn('status', ['Disetujui', 'approved'])->count(),
            'rejected'      => $allRequisitions->whereIn('status', ['Ditolak', 'rejected'])->count(),
            'new_employees' => Application::where('status', 'accepted')
                ->whereHas('jobPosting.requisition.position', fn ($q) => $q->where('division_id', $divisionId))
                ->count(),
        ];

        $requisitions = $allRequisitions->take(5);

        $jobPostings = JobPosting::with(['requisition'])
            ->withCount(['applications as accepted_count' => fn ($q) => $q->where('status', 'accepted')])
            ->whereHas('requisition.position', fn ($q) => $q->where('division_id', $divisionId))
            ->latest()
            ->take(5)
            ->get();

        $newEmployees = Application::with(['applicant', 'jobPosting.requisition.position'])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition.position', fn ($q) => $q->where('division_id', $divisionId))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('manager.dashboard.index', compact('newEmployees', 'metrics', 'requisitions', 'jobPostings'));
    }

    public function hrd()
    {
        $pendingRequisitionsCount = Requisition::whereIn('status', ['Diajukan', 'submitted'])->count();
        $needScreeningCount = Application::where('status', 'submitted')->count();
        $scheduledInterviewsCount = Interview::where('status', 'scheduled')->count();
        $readyForDecisionCount = Application::where('status', 'interview')
            ->whereHas('interviews', fn ($q) => $q->where('status', 'completed')->whereNotNull('score'))
            ->count();

        $metrics = [
            'pending_requisitions' => $pendingRequisitionsCount,
            'need_screening'       => $needScreeningCount,
            'scheduled_interviews' => $scheduledInterviewsCount,
            'ready_decision'       => $readyForDecisionCount,
        ];

        $funnel = [
            'Dikirim'   => Application::where('status', 'submitted')->count(),
            'Direview'  => Application::where('status', 'screening_passed')->count(),
            'Wawancara' => Application::where('status', 'interview')->count(),
            'Diterima'  => Application::where('status', 'accepted')->count(),
        ];

        $upcomingInterviews = Interview::with(['application.applicant', 'application.jobPosting', 'interviewer'])
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->take(4)
            ->get();

        $latestRequisitions = Requisition::with(['position.division', 'requester'])
            ->whereIn('status', ['Diajukan', 'submitted'])
            ->latest()
            ->take(3)
            ->get();

        $closingSoonJobs = JobPosting::with(['requisition.position'])
            ->withCount(['applications', 'applications as accepted_count' => fn ($q) => $q->where('status', 'accepted')])
            ->where('status', 'open')
            ->orderBy('deadline')
            ->take(3)
            ->get();

        return view('hrd.dashboard.index', compact(
            'metrics',
            'funnel',
            'upcomingInterviews',
            'latestRequisitions',
            'closingSoonJobs'
        ));
    }

    public function applicant()
    {
        $user = Auth::user();
        $activeStatuses = ['submitted', 'screening_passed', 'interview'];

        $applications = Application::with(['jobPosting.requisition.position.division', 'interviews'])
            ->where('applicant_id', $user->id)
            ->orderByDesc('submitted_at')
            ->get();

        $activeCount = $applications->whereIn('status', $activeStatuses)->count();

        $upcomingInterview = Interview::with(['application.jobPosting'])
            ->whereHas('application', fn ($q) => $q->where('applicant_id', $user->id))
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->first();

        $upcomingInterviewCount = Interview::whereHas('application', fn ($q) => $q->where('applicant_id', $user->id))
            ->where('status', 'scheduled')
            ->count();

        $profile = ApplicantProfile::where('user_id', $user->id)->first();
        $hasPhone = filled($profile?->phone);
        $hasEducation = filled($profile?->education);
        $completeness = ($hasPhone && $hasEducation) ? 100 : (($hasPhone || $hasEducation) ? 50 : 0);

        $notifications = ApplicationNotification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $appliedJobIds = $applications->pluck('job_posting_id')->all();
        $recommendedJobs = JobPosting::with(['requisition.position.division'])
            ->where('status', 'open')
            ->whereDate('deadline', '>=', now('Asia/Jakarta')->toDateString())
            ->when(count($appliedJobIds) > 0, fn ($q) => $q->whereNotIn('id', $appliedJobIds))
            ->orderBy('deadline')
            ->take(3)
            ->get();

        return view('applicant.dashboard.index', compact(
            'applications',
            'activeCount',
            'upcomingInterview',
            'upcomingInterviewCount',
            'profile',
            'completeness',
            'notifications',
            'recommendedJobs'
        ));
    }

    public function hrdEmployees()
    {
        $newEmployees = Application::with(['applicant', 'jobPosting.requisition.position.division'])
            ->where('status', 'accepted')
            ->orderByDesc('updated_at')
            ->get();

        return view('hrd.employees.index', compact('newEmployees'));
    }

    public function hrdUsers()
    {
        $users = User::with('division')->orderBy('role')->orderBy('name')->get();

        return view('hrd.users.index', compact('users'));
    }

    public function hrdStatusHistories()
    {
        $histories = StatusHistory::with(['changer', 'subject'])
            ->orderByDesc('created_at')
            ->take(100)
            ->get();

        return view('hrd.status_histories.index', compact('histories'));
    }

    public function hrdReports()
    {
        $statusCounts = [
            'submitted'        => Application::where('status', 'submitted')->count(),
            'screening_passed' => Application::where('status', 'screening_passed')->count(),
            'interview'        => Application::where('status', 'interview')->count(),
            'accepted'         => Application::where('status', 'accepted')->count(),
            'screening_failed' => Application::whereIn('status', ['screening_failed', 'failed'])->count(),
            'rejected'         => Application::where('status', 'rejected')->count(),
            'cancelled'        => Application::where('status', 'cancelled')->count(),
        ];

        $jobSummaries = JobPosting::with(['requisition.position.division'])
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->where('status', 'accepted'),
            ])
            ->withCount([
                'applications as interview_count' => fn ($q) => $q->whereHas('interviews'),
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('hrd.reports.index', compact('statusCounts', 'jobSummaries'));
    }

    public function hrdReportsExport()
    {
        $jobSummaries = JobPosting::with(['requisition.position.division'])
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->where('status', 'accepted'),
                'applications as interview_count' => fn ($q) => $q->whereHas('interviews'),
            ])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'laporan-rekrutmen-' . now('Asia/Jakarta')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($jobSummaries) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Lowongan', 'Divisi', 'Pelamar', 'Wawancara', 'Terisi', 'Kuota', 'Status']);
            foreach ($jobSummaries as $job) {
                fputcsv($out, [
                    $job->title,
                    $job->requisition->position->division->name ?? '-',
                    $job->applications_count,
                    $job->interview_count,
                    $job->accepted_count,
                    $job->quota,
                    $job->status === 'open' ? 'Dibuka' : 'Ditutup',
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function hrdTrash()
    {
        $trashedRequisitions = Requisition::onlyTrashed()
            ->with(['position.division', 'requester'])
            ->orderByDesc('deleted_at')
            ->get();

        $trashedJobs = JobPosting::onlyTrashed()
            ->with(['requisition.position.division'])
            ->orderByDesc('deleted_at')
            ->get();

        return view('hrd.trash.index', compact('trashedRequisitions', 'trashedJobs'));
    }

    public function notifications()
    {
        $notifications = ApplicationNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('applicant.notifications.index', compact('notifications'));
    }
}
