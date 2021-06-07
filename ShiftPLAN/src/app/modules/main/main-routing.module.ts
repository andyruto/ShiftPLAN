/**
 * main-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for the main routing module.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */

 import { NgModule } from '@angular/core';
 import { RouterModule, Routes } from '@angular/router';
 import { AdminComponent } from './admin/admin.component';
 import { HomeComponent } from './home/home.component';
 import { ProfileComponent } from './profile/profile.component';
 import { SettingsComponent } from './settings/settings.component';
 import { SettingsInfoComponent } from './settings-info/settings-info.component'
 import { SettingsPasswordComponent } from './settings-password/settings-password.component'
 import { SettingsThemeComponent } from './settings-theme/settings-theme.component'
 import { SettingsAdaptScreenComponent } from './settings-adapt-screen/settings-adapt-screen.component'
 import { ShiftsComponent } from './shifts/shifts.component';
 import { StatisticsComponent } from './statistics/statistics.component';
 import { TasksComponent } from './tasks/tasks.component';
 import { MainComponent } from './main.component'
 import { MainNavigationComponent } from './main-navigation/main-navigation.component'
 
 const routes: Routes = [
  { path: '', component: MainComponent, children: [
    {path: 'main', component: MainNavigationComponent, children: [
      {path: 'home', component: HomeComponent},
      {path: 'shifts', component: ShiftsComponent},
      {path: 'tasks', component: TasksComponent},
      {path: 'statistics', component: StatisticsComponent},
      {path: 'profile', component: ProfileComponent},
      {path: 'settings', component: SettingsComponent},
      {path: 'admin', component: AdminComponent},
    ]},
    {path: 'settings-info', component: SettingsInfoComponent},
    {path: 'settings-password', component: SettingsPasswordComponent},
    {path: 'settings-theme', component: SettingsThemeComponent},
    {path: 'settings-adapt-screen', component: SettingsAdaptScreenComponent}
  ]}
 ];
 
 @NgModule({
   imports: [RouterModule.forChild(routes)],
   exports: [RouterModule]
 })
 export class MainRoutingModule { }
 