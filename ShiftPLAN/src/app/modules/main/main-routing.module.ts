/**
 * main-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for the main routing module.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */

 import { NgModule } from '@angular/core';
 import { RouterModule, Routes } from '@angular/router';
 import { AdminComponent } from './components/admin/admin.component';
 import { AdminAddComponent } from './components/admin-add/admin-add.component';
 import { AdminUserComponent } from './components/admin-user/admin-user.component';
 import { HomeComponent } from './components/home/home.component';
 import { ProfileComponent } from './components/profile/profile.component';
 import { SettingsComponent } from './components/settings/settings.component';
 import { SettingsInfoComponent } from './components/settings-info/settings-info.component'
 import { SettingsPasswordComponent } from './components/settings-password/settings-password.component'
 import { SettingsThemeComponent } from './components/settings-theme/settings-theme.component'
 import { SettingsAdaptScreenComponent } from './components/settings-adapt-screen/settings-adapt-screen.component'
 import { ShiftsComponent } from './components/shifts/shifts.component';
 import { ShiftsAddComponent } from './components/shifts-add/shifts-add.component';
 import { StatisticsComponent } from './components/statistics/statistics.component';
 import { TasksComponent } from './components/tasks/tasks.component';
 import { TasksAddComponent } from './components/tasks-add/tasks-add.component';
 import { TasksTaskComponent } from './components/tasks-task/tasks-task.component';
 import { MainComponent } from './main.component'
 import { MainNavigationComponent } from './components/main-navigation/main-navigation.component'
 
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
    {path: 'settings-adapt-screen', component: SettingsAdaptScreenComponent},
    {path: 'admin-add', component: AdminAddComponent },
    {path: 'admin-user', component: AdminUserComponent },
    {path: 'tasks-add', component: TasksAddComponent },
    {path: 'tasks-task', component: TasksTaskComponent }
    {path: 'shifts-add', component: ShiftsAddComponent }
  ]}
 ];
 
 @NgModule({
   imports: [RouterModule.forChild(routes)],
   exports: [RouterModule]
 })
 export class MainRoutingModule { }
 