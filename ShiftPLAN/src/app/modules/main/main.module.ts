/**
 * main.module.ts
 * 
 * Module that contains all main components.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { SharedModule } from '../shared/shared.module';
import { MainRoutingModule } from './main-routing.module';
import { ViewElementsModule } from '../view-elements/view-elements.module';
import { HomeComponent } from './home/home.component';
import { ShiftsComponent } from './shifts/shifts.component';
import { TasksComponent } from './tasks/tasks.component';
import { StatisticsComponent } from './statistics/statistics.component';
import { ProfileComponent } from './profile/profile.component';
import { SettingsComponent } from './settings/settings.component';
import { AdminComponent } from './admin/admin.component';
import { MainComponent } from './main.component';
import { MainNavigationComponent } from './main-navigation/main-navigation.component';

@NgModule({
  declarations: [
    HomeComponent,
    ShiftsComponent,
    TasksComponent,
    StatisticsComponent,
    ProfileComponent,
    SettingsComponent,
    AdminComponent,
    MainComponent,
    MainNavigationComponent
  ],
  imports: [
    SharedModule,
    MainRoutingModule,
    ViewElementsModule
  ]
})
export class MainModule { }
