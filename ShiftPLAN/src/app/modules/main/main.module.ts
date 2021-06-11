/**
 * main.module.ts
 * 
 * Module that contains all main components.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { MainRoutingModule } from './main-routing.module';
import { ViewElementsModule } from '../view-elements/view-elements.module';
import { SharedModule } from '../shared/shared.module';
import { MatListModule } from '@angular/material/list';
import { MatDividerModule } from '@angular/material/divider';
import { MatRadioModule } from '@angular/material/radio';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatInputModule } from '@angular/material/input';
import { MatTabsModule } from '@angular/material/tabs';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { MatCheckboxModule } from '@angular/material/checkbox';

import { HomeComponent } from './home/home.component';
import { ShiftsComponent } from './shifts/shifts.component';
import { TasksComponent } from './tasks/tasks.component';
import { StatisticsComponent } from './statistics/statistics.component';
import { ProfileComponent } from './profile/profile.component';
import { SettingsComponent } from './settings/settings.component';
import { AdminComponent } from './admin/admin.component';
import { MainComponent } from './main.component';
import { MainNavigationComponent } from './main-navigation/main-navigation.component';
import { SettingsInfoComponent } from './settings-info/settings-info.component';
import { SettingsPasswordComponent } from './settings-password/settings-password.component';
import { SettingsThemeComponent } from './settings-theme/settings-theme.component';
import { SettingsAdaptScreenComponent } from './settings-adapt-screen/settings-adapt-screen.component';
import { AdminAddComponent } from './admin-add/admin-add.component';
import { AdminUserComponent } from './admin-user/admin-user.component';
import { TasksAddComponent } from './tasks-add/tasks-add.component';

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
    MainNavigationComponent,
    SettingsInfoComponent,
    SettingsPasswordComponent,
    SettingsThemeComponent,
    SettingsAdaptScreenComponent,
    AdminAddComponent,
    AdminUserComponent,
    TasksAddComponent
  ],
  imports: [
    ViewElementsModule,
    SharedModule,
    MatListModule,
    MatDividerModule,
    MainRoutingModule,
    MatRadioModule,
    MatSlideToggleModule,
    MatInputModule,
    MatTabsModule,
    ScrollingModule,
    MatCheckboxModule
  ]
})
export class MainModule { }
