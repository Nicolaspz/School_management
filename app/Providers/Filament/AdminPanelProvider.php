<?php

namespace App\Providers\Filament;

use App\Filament\Resources\PeriodeResource;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\LegacyComponents\Widget;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Resources\CategoryNilaiResource;
use App\Filament\Resources\ClassroomHasSubjectResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\NotaResource;
use App\Filament\Resources\StudentHasClassesResource;
use App\Filament\Resources\StudentsResource;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\UserResource;
use App\Models\Periode;
use App\Models\StudentHasClasses;
use App\Models\Teacher;
use App\Models\Team;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            /*->plugin([
                //FilamentSpatieRolesPermissionsPlugin::make()
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])*/
                //FilamentSpatieRolesPermissionsPlugin::make())
            //->tenant(Team::class)
            //->tenantRegistration(RegisterTeam::class)
            ->plugins([
                 //FilamentSpatieRolesPermissionsPlugin::make()
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ])
      /*      ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                    ->items([
                        NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                        ->url(fn (): string => Dashboard::getUrl()),
                    ]),
                    NavigationGroup::make('Académico')
                        ->items([
                            ...TeacherResource::getNavigationItems(),
                            ...StudentsResource::getNavigationItems(),
                            ...StudentHasClassesResource::getNavigationItems(),
                            ...SubjectResource::getNavigationItems(),
                            ...NotaResource::getNavigationItems(),
                            ...ClassroomHasSubjectResource::getNavigationItems(),


                        ]),
                        NavigationGroup::make('Source')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...DepartmentResource::getNavigationItems(),

                        ]),

                        NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            NavigationItem::make('Roles')
                            ->icon('heroicon-o-user-group')
                            ->isActiveWhen(fn (): bool => request()->routeIs([
                                'filament.admin.resources.roles.index',
                                'filament.admin.resources.roles.create',
                                'filament.admin.resources.roles.view',
                                'filament.admin.resources.roles.edit'
                            ]))
                            ->url(fn (): string => '/admin/roles'),
                            NavigationItem::make('Permissions')
                            ->icon('heroicon-o-lock-closed')
                            ->isActiveWhen(fn (): bool => request()->routeIs([
                                'filament.admin.resources.permissions.index',
                                'filament.admin.resources.permissions.create',
                                'filament.admin.resources.permissions.view',
                                'filament.admin.resources.permissions.edit'
                            ]))
                            ->url(fn (): string => '/admin/permissions'),
                            ...UserResource::getNavigationItems(),

                        ]),
                ]);
            })*/
            ->databaseNotifications();
    }

    public function boot(): void
    {
        Filament::serving(function(){
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                ->label('Perfil')
                //->url(PeriodeResource::getUrl())
               ->icon('heroicon-s-cog'),
            ]);
        });
    }

}
