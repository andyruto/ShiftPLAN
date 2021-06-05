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
    //paths where whole screen is reused
  ]}
 ];
 
 @NgModule({
   imports: [RouterModule.forChild(routes)],
   exports: [RouterModule]
 })
 export class MainRoutingModule { }
 