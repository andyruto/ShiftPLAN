import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home/home.component';
import { ShiftsComponent } from './shifts/shifts.component';
import { TasksComponent } from './tasks/tasks.component';
import { StatisticsComponent } from './statistics/statistics.component';
import { ProfileComponent } from './profile/profile.component';
import { SettingsComponent } from './settings/settings.component';
import { AdminComponent } from './admin/admin.component';



@NgModule({
  declarations: [
    HomeComponent,
    ShiftsComponent,
    TasksComponent,
    StatisticsComponent,
    ProfileComponent,
    SettingsComponent,
    AdminComponent
  ],
  imports: [
    CommonModule
  ]
})
export class MainModule { }
