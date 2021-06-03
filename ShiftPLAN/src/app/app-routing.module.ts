/**
 * app-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-03 / Anne Naumann
 */

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AdminComponent } from './modules/main/admin/admin.component';
import { HomeComponent } from './modules/main/home/home.component';
import { ProfileComponent } from './modules/main/profile/profile.component';
import { SettingsComponent } from './modules/main/settings/settings.component';
import { ShiftsComponent } from './modules/main/shifts/shifts.component';
import { StatisticsComponent } from './modules/main/statistics/statistics.component';
import { TasksComponent } from './modules/main/tasks/tasks.component';

const routes: Routes = [
  {path: 'home', component: HomeComponent},
  {path: 'shifts', component: ShiftsComponent},
  {path: 'tasks', component: TasksComponent},
  {path: 'statistics', component: StatisticsComponent},
  {path: 'profile', component: ProfileComponent},
  {path: 'settings', component: SettingsComponent},
  {path: 'admin', component: AdminComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {useHash: true})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
