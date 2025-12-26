<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class NavbarComposer
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function compose(View $view)
    {
        // Só injeta se estiver logado
        if (Auth::check()) {
            $notifications = $this->dashboardService->getNotificationsData();
            $theme = session('theme', 'light');

            $view->with('navbarNotifications', $notifications);
            $view->with('currentTheme', $theme);
        }
    }
}
