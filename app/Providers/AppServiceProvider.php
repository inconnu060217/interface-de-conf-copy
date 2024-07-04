<?php

namespace App\Providers;

use App\Services\call\entryPoints\EntryPointRepository;
use App\Services\call\entryPoints\EntryPointService;
use App\Services\call\entryPoints\IEntryPointRepository;
use App\Services\call\entryPoints\IEntryPointService;
use App\Services\call\entryPointTypes\EntryPointTypeRepository;
use App\Services\call\entryPointTypes\EntryPointTypeService;
use App\Services\call\entryPointTypes\IEntryPointTypeRepository;
use App\Services\call\entryPointTypes\IEntryPointTypeService;
use App\Services\call\plannings\IPlanningRepository;
use App\Services\call\plannings\IPlanningService;
use App\Services\call\plannings\PlanningRepository;
use App\Services\call\plannings\PlanningService;
use App\Services\call\queueCalls\IQueueCallRepository;
use App\Services\call\queueCalls\IQueueCallService;
use App\Services\call\queueCalls\QueueCallRepository;
use App\Services\call\queueCalls\QueueCallService;
use App\Services\connexionsKiamos\ConnexionKiamoService;
use App\Services\connexionsKiamos\IConnexionKiamoService;
use App\Services\environmentParameters\EnvironmentParameterRepository;
use App\Services\environmentParameters\EnvironmentParameterService;
use App\Services\environmentParameters\IEnvironmentParameterRepository;
use App\Services\environmentParameters\IEnvironmentParameterService;
use App\Services\groups\GroupRepository;
use App\Services\groups\GroupService;
use App\Services\groups\IGroupRepository;
use App\Services\groups\IGroupService;
use App\Services\logErrors\ILogErrorService;
use App\Services\logErrors\LogErrorService;
use App\Services\mainMenus\IMainMenuRepository;
use App\Services\mainMenus\IMainMenuService;
use App\Services\mainMenus\MainMenuRepository;
use App\Services\mainMenus\MainMenuService;
use App\Services\profile\IProfileRepository;
use App\Services\profile\IProfileService;
use App\Services\profile\ProfileRepository;
use App\Services\profile\ProfileService;
use App\Services\requestsCurls\IRequestCurlService;
use App\Services\requestsCurls\RequestCurlService;
use App\Services\secondaryMenus\ISecondaryMenuRepository;
use App\Services\secondaryMenus\ISecondaryMenuService;
use App\Services\secondaryMenus\SecondaryMenuRepository;
use App\Services\secondaryMenus\SecondaryMenuService;
use App\Services\webService\planningWebService\IPlanningWebServiceService;
use App\Services\webService\planningWebService\PlanningWebServiceService;
use App\Services\webService\queueCallWebServices\IQueueCallWebServiceService;
use App\Services\webService\queueCallWebServices\QueueCallWebServiceService;
use App\Services\webService\svisWebServices\ISviWebServiceService;
use App\Services\webService\svisWebServices\SviWebServiceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ILogErrorService::class, LogErrorService::class);
        $this->app->bind(IMainMenuService::class, MainMenuService::class);
        $this->app->bind(IMainMenuRepository::class, MainMenuRepository::class);
        $this->app->bind(ISecondaryMenuService::class, SecondaryMenuService::class);
        $this->app->bind(ISecondaryMenuRepository::class, SecondaryMenuRepository::class);
        $this->app->bind(IEnvironmentParameterRepository::class, EnvironmentParameterRepository::class);
        $this->app->bind(IEnvironmentParameterService::class, EnvironmentParameterService::class);
        $this->app->bind(IEntryPointTypeRepository::class, EntryPointTypeRepository::class);
        $this->app->bind(IEntryPointTypeService::class, EntryPointTypeService::class);
        $this->app->bind(IEntryPointRepository::class, EntryPointRepository::class);
        $this->app->bind(IEntryPointService::class, EntryPointService::class);
        $this->app->bind(IRequestCurlService::class, RequestCurlService::class);
        $this->app->bind(IProfileRepository::class, ProfileRepository::class);
        $this->app->bind(IProfileService::class, ProfileService::class);
        $this->app->bind(IConnexionKiamoService::class, ConnexionKiamoService::class);
        $this->app->bind(IGroupRepository::class, GroupRepository::class);
        $this->app->bind(IGroupService::class, GroupService::class);
        $this->app->bind(ISviWebServiceService::class, SviWebServiceService::class);
        $this->app->bind(IQueueCallWebServiceService::class, QueueCallWebServiceService::class);
        $this->app->bind(IQueueCallService::class, QueueCallService::class);
        $this->app->bind(IQueueCallRepository::class, QueueCallRepository::class);
        $this->app->bind(IPlanningRepository::class, PlanningRepository::class);
        $this->app->bind(IPlanningService::class, PlanningService::class);
        $this->app->bind(IPlanningWebServiceService::class, PlanningWebServiceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
